/**
 * Holds all the methods to do with the Three JS Canvas
 */

export default class Canvas {

	constructor(App) {

		this.App = App;
		this.Objects = this.App.Objects;
		this.Rooms = this.App.Rooms;

		this.THREE = require('Three');

		this.initCanvas();
		this.animate();

		this.App.Debug.log('Canvas initialised');

	}

	initVars() {

		this.camera;
        this.scene;
        this.renderer;
        this.container = document.querySelector('[data-360-space]');
        this.layer;
        this.mainMesh;
        this.mainGeometry;
        this.uv;

		this.isUserInteracting = false;
        this.onMouseDownMouseX = 0;
        this.onMouseDownMouseY = 0;
        this.lon = 0;
        this.onMouseDownLon = 0;
        this.lat = 0;
        this.onMouseDownLat = 0;
        this.phi = 0;
        this.theta = 0; // setup camera

        //arrow room connector
        this.sprite;
        this.map;
        this.spriteGroup;


        //mouse variables
        this.raycaster;
        this.mouse;
        this.onClickPosition;

        //check if a tool is active
        this.isToolActive = false;
	}

	initCanvas() {

		this.initVars();


        this.camera = new this.THREE.PerspectiveCamera( 90, window.innerWidth / window.innerHeight, 1, 2000 );
        this.camera.target = new this.THREE.Vector3( 0, 0, 0 );

        this.scene = new this.THREE.Scene();

        this.mainGeometry = new this.THREE.SphereBufferGeometry( 500, 100, 100 );
        // invert the mainGeometry on the x-axis so that all of the faces point inward
        this.mainGeometry.scale( - 1, 1, 1 );

        this.makeMaterial();

        this.mainMesh = new this.THREE.Mesh( this.mainGeometry, this.material );

        this.mainMesh.renderOrder = 10;

        this.layer = new this.THREE.Object3D();

        this.spriteGroup = new this.THREE.Group();

        this.scene.add(this.mainMesh);

        //init mouse variables
        this.raycaster = new this.THREE.Raycaster();
        this.mouse = new this.THREE.Vector3();
        this.onClickPosition = new this.THREE.Vector3();

        //sprite for room connector
        this.textureLoader = new this.THREE.TextureLoader();
        this.map = this.textureLoader.load("/img/link.png");
        this.arrowMaterial = new this.THREE.SpriteMaterial({map: this.map});

        this.active = this.textureLoader.load("/img/activeLink.png");
        this.activeArrowMaterial = new this.THREE.SpriteMaterial({map: this.active});

        this.scene.add(this.layer);

        this.mainRender();

        this.App.Instances.UiController.hideAppLoader();

        //default event listeners for moving around the scene - ignores tools
        document.addEventListener( 'mousedown', (e) => { this.onDocumentMouseDown(e) });
        document.addEventListener( 'touchstart', (e) => { this.onDocumentMouseDown(e) });

        document.addEventListener( 'mousemove', (e) => { this.onDocumentMouseMove(e) });
        document.addEventListener( 'touchmove', (e) => { this.onDocumentMouseMove(e) });

        document.addEventListener( 'mouseup', (e) => { this.onDocumentMouseUp(e) });
        document.addEventListener( 'touchup', (e) => { this.onDocumentMouseUp(e) });

        window.addEventListener( 'resize', (e) => { this.onWindowResize(e) });

	}

	makeMaterial() {

		let firstImage  = this.Rooms[0].image.src;

		this.material = new this.THREE.MeshBasicMaterial( {
            map: new this.THREE.TextureLoader().load(firstImage)
        });
	}

	loadInImage(Room) {

		let thisToPass = this;

		setTimeout(function() {
			let image = Room.image.src;
			thisToPass.material.map.image.src = image;
			thisToPass.material.map.needsUpdate = true;

			thisToPass.App.Instances.UiController.hideAppLoader();
		}, 200);

        this.clearRoomConnectors();

        //check if there is data in the room
        if(Room.links.length){
            console.log("Room link length: ",Room.links.length);
            console.log("Room Id: ",Room.links);
        }



        //load data
        for (var i = Room.links.length - 1; i >= 0; i--) {
            this.addNewRoomConnector(Room.links[i].roomName);
            this.updateRoomConnector(Room.links[i].coordinates.x, Room.links[i].coordinates.y, Room.links[i].coordinates.z);
        }

	}

	mainRender() {
		this.renderer = new this.THREE.WebGLRenderer();
        this.renderer.setPixelRatio( window.devicePixelRatio );
        this.renderer.setSize( window.innerWidth, window.innerHeight );
        this.container.appendChild( this.renderer.domElement );
	}

	animate() {
		requestAnimationFrame(() => { this.animate() } );
		this.update();
	}

	update() {
        this.lat = Math.max( - 90, Math.min( 85, this.lat ) ); //restrict Y rotation to not show tripod change to variable later
        this.phi = this.THREE.Math.degToRad( 90 - this.lat );
        this.theta = this.THREE.Math.degToRad( this.lon );
        this.camera.target.x = 500 * Math.sin( this.phi ) * Math.cos( this.theta );
        this.camera.target.y = 500 * Math.cos( this.phi );
        this.camera.target.z = 500 * Math.sin( this.phi ) * Math.sin( this.theta );
        this.camera.lookAt( this.camera.target );

        this.renderer.render( this.scene, this.camera );
    }

    onWindowResize() {
        this.camera.aspect = window.innerWidth / window.innerHeight;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize( window.innerWidth, window.innerHeight );
    }


    onDocumentMouseDown( event ) {
        // event.preventDefault(); prevents default html and javascript events from working not good if you have html elements that are interactive
        this.isUserInteracting = true;
        this.onMouseDownMouseX = event.clientX;
        this.onMouseDownMouseY = event.clientY;
        this.onMouseDownLon = this.lon;
        this.onMouseDownLat = this.lat;

        if (this.layer.children.length > 0){
            this.changeImageFromObject(event); // call changing scene function when there's a room connector at the mouse position
        }
    }

    onDocumentMouseMove( event ) {
        if ( this.isUserInteracting ) {
            this.lon = ( this.onMouseDownMouseX - event.clientX ) * 0.1 + this.onMouseDownLon;
            this.lat = ( event.clientY - this.onMouseDownMouseY ) * 0.1 + this.onMouseDownLat;
        }
    }

    onDocumentMouseUp( event ) {
        this.isUserInteracting = false;
       // this.animate(); // update the scene incase of new objects
    }

    onDocumentMouseMove( event ) {

        if (event.target.matches('[data-360-space] canvas')) {
            if ( this.isUserInteracting ) {
                this.lon = ( this.onMouseDownMouseX - event.clientX ) * 0.1 + this.onMouseDownLon;
                this.lat = ( event.clientY - this.onMouseDownMouseY ) * 0.1 + this.onMouseDownLat;
            }
        }

    }

     //stand alone function for returning the mouse exact position in the scene
      returnMousePos(e) {
        //get mouse relative to dom
        this.rect = this.container.getBoundingClientRect();
        this.array = [ ( e.clientX - this.rect.left ) / this.rect.width,  (  e.clientY - this.rect.top ) / this.rect.height,( e.clientX -  e.clientY) / (this.rect.width - this.rect.height)];

        //get Intersects
        this.onClickPosition.fromArray( this.array );
        this.mouse.set( ( this.onClickPosition.x * 2 ) - 1, - ( this.onClickPosition.y * 2 ) + 1, ( this.onClickPosition.z * 2 ) + 1 );
        this.raycaster.setFromCamera( this.mouse, this.camera );
        this.intersects = this.raycaster.intersectObjects( this.scene.children );

        //for each object return data about the mouse
        for( var i = 0; i <  this.intersects.length; i++ ) {
            this.intersection =  this.intersects[ i ],
            this.obj = this.intersection.object;

            return [this.intersects[i].point.x, this.intersects[i].point.y, this.intersects[i].point.z];
        }
    }


    //standalone function to return the 1st object from the mouse to the scene and change scene if it's a room connector
    changeImageFromObject(e){
        //check if a tool is active
        if (this.isToolActive != true) {
            //get mouse relative to dom
            this.rect = this.container.getBoundingClientRect();
            this.array = [ ( e.clientX - this.rect.left ) / this.rect.width,  (  e.clientY - this.rect.top ) / this.rect.height,( e.clientX -  e.clientY) / (this.rect.width - this.rect.height)];

            //get Intersects
            this.onClickPosition.fromArray( this.array );
            this.mouse.set( ( this.onClickPosition.x * 2 ) - 1, - ( this.onClickPosition.y * 2 ) + 1, ( this.onClickPosition.z * 2 ) + 1 );
            this.raycaster.setFromCamera( this.mouse, this.camera );
            this.intersects = this.raycaster.intersectObjects( this.layer.children );
            if (this.intersects.length > 0) {
                this.obj = this.intersects[0].object;
                if (this.obj.name) {
                    //change element
                    this.upload = document.getElementById(this.obj.name);

                    this.currentActiveUpload = document.querySelector('[data-upload].active');
                    this.currentActiveUpload.classList.remove('active');

                    this.upload.classList.add('active');

                    this.loadInImage(this.Rooms[this.obj.name]);
                }
            }
        }
    }

    addNewRoomConnector(id ){
        this.width = this.arrowMaterial.map.image.width / 2;
        this.height = this.arrowMaterial.map.image.height / 2;
        this.sprite = new this.THREE.Sprite(this.arrowMaterial);
        this.sprite.scale.set(this.width, this.height);

        //set name to room id to connect. id is read only so we use name
        this.sprite.name = id;
        this.spriteGroup.add(this.sprite);
        this.layer.add(this.sprite);

        return this.sprite;
    }

    updateRoomConnector(posX, posY, posZ){

        //if position is outside of bounds move it back
        if (posX > 450) {
           posX = posX - 45;
        }
        if(posX < -450){
           posX = posX + 45;
        }

        if(posY > 0){
           posY = posY - 45;
        }
        if(posY < 0){
           posY = posY + 45;
        }

        if(posZ > 0){
           posZ = posZ - 45;
        }
        if(posZ < 0){
           posZ = posZ + 45;
        }

        this.sprite.position.set(posX, posY, posZ);
        this.animate();
        return this.sprite;
    }

    //simple move object function, set color to white from activeObject function
    moveObject(newX, newY, newZ, object){
        this.updateRoomConnector(newX, newY, newZ);
        object.material.color.setHex(0xffffff);
        this.animate();
    }


    //get 1st object that intersects with the mouse and set it's color to red and activate it - helper function for ObjectPicker.js
    activateObject(e){
        //get mouse relative to dom
        this.rect = this.container.getBoundingClientRect();
        this.array = [ ( e.clientX - this.rect.left ) / this.rect.width,  (  e.clientY - this.rect.top ) / this.rect.height,( e.clientX -  e.clientY) / (this.rect.width - this.rect.height)];

        //get Intersects
        this.onClickPosition.fromArray( this.array );
        this.mouse.set( ( this.onClickPosition.x * 2 ) - 1, - ( this.onClickPosition.y * 2 ) + 1, ( this.onClickPosition.z * 2 ) + 1 );
        this.raycaster.setFromCamera( this.mouse, this.camera );
        this.intersects = this.raycaster.intersectObjects( this.layer.children );
        this.obj = this.intersects[0].object;

        if(this.obj.material == this.arrowMaterial){
            this.obj.material = this.activeArrowMaterial;
            return this.obj;
        }
        if(this.obj.material ==  this.activeArrowMaterial){
            this.obj.material = this.arrowMaterial;
        }
    }

    //clean room connectors off scene
    clearRoomConnectors(){
        if(this.layer.children.length > 0){
            for (var i = this.layer.children.length -1; i >= 0; i--) {
                    this.layer.remove(this.layer.children[i]);
            }
        }
    }

    removeRoomConnectors(id){
        this.layer.remove(this.layer.children[id]);
        this.animate();
        console.log("Removing: ", this.layer.children)
    }

}
