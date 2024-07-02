<template>
    <div id="file-drag-drop">
        <form ref="fileform">

        </form>
        <progress max="100" v-if="uploadPercentage" :value.prop="uploadPercentage"></progress>
        <span class="drop-files" @click="selectFile"> Arrastre las imágenes o haga click aquí</span>
        <div class="file-listing text-center" v-if="!multiple && file">
            <img  class="preview" v-bind:ref="'preview-file'"/>
            {{ file.name }}
            <span  v-on:click="removeFile( 'file' )">
                <i class="fas fa-times"></i>
            </span>
        </div>
        <div  v-show="getFiles">
            <div v-for="(file, key) in getFiles" class="file-listing" :key="key">
            <img class="preview" v-bind:ref="'preview'+parseInt( key )"/>
            {{ file.name }}
            <span  v-on:click="removeFile( key )">
                <i class="fas fa-times"></i>
            </span>
        </div>
        </div>
        

        <input class="d-none" type="file" ref="here" @change="handleFileChange" :multiple="multiple">
<!--        <div class="remove-container">-->
<!--            <a class="remove" v-on:click="removeFile( key )">Remove</a>-->
<!--        </div>-->
        <div class="d-flex justify-content-center">
            <!-- <button class="btn btn-primary  m-2" v-on:click="submitFiles()" v-show="files.length > 0 || file">
                <i class="fas fa-pen"></i>
               Guardar
            </button> -->
            <!-- <button class="btn btn-primary   m-2"  @click="close">Cancelar</button> -->
        </div>


    </div>
</template>

<script>
    import axios from 'axios'
    
    export default {
        name: 'DragDrop',
        props:{
            uploadUrl:{
                type:String,
                required:true,
            },
            multiple:{
                type:Boolean,
                default:true
            },
            id:{
                type:Number,
                required:false,
            }
        },
        data(){
            return {
                dragAndDropCapable: false,
                files: [],
                file:'',
                uploadPercentage: 0
            }
        },
        computed:{
            getFiles(){
                return this.files
            }
        },
        mounted(){
            var self = this
             eventBus.$on('upload-images', (data) => {

                self.submitFiles(data)
            })
            /*
                Determine if drag and drop functionality is capable in the browser
              */
            this.dragAndDropCapable = this.determineDragAndDropCapable();

            /*
              If drag and drop capable, then we continue to bind events to our elements.
            */
            if( this.dragAndDropCapable ){
                /*
                  Listen to all of the drag events and bind an event listener to each
                  for the fileform.
                */
                ['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'].forEach( function( evt ) {
                    /*
                      For each event add an event listener that prevents the default action
                      (opening the file in the browser) and stop the propagation of the event (so
                      no other elements open the file in the browser)
                    */
                    this.$refs.fileform.addEventListener(evt, function(e){
                        e.preventDefault();
                        e.stopPropagation();
                    }.bind(this), false);
                }.bind(this));

                /*
                  Add an event listener for drop to the form
                */
                this.$refs.fileform.addEventListener('drop', function(e){
                    /*
                      Capture the files from the drop event and add them to our local files
                      array.
                      use this  e.dataTransfer.files.length for multiples files
                    */
                    if(this.multiple){
                        for( let i = 0; i < e.dataTransfer.files.length; i++ ){
                            this.files.push( e.dataTransfer.files[i] );
                        }
                    }else{
                        this.file = e.dataTransfer.files[0]
                    }

                    this.getImagePreviews();
                }.bind(this));
            }
        },
        methods:{
            close(){
                this.$emit("close");
                this.$emit("updated");
            },
            selectFile() {
                this.$refs['here'].click()
            },
            handleFileChange(e){
                this.files =  Array.from(e.target.files)
                this.getImagePreviews()
            },
            removeFile( key ){
     
                if(key === 'file'){
                    this.file = ''
                    this.$refs['preview-file'].value = ''
                    this.$refs['here'].value = ''
                    return
                }
              
                this.files.splice( key, 1 );
            },
            determineDragAndDropCapable(){
                /*
                  Create a test element to see if certain events
                  are present that let us do drag and drop.
                */
                var div = document.createElement('div');

                /*
                  Check to see if the `draggable` event is in the element
                  or the `ondragstart` and `ondrop` events are in the element. If
                  they are, then we have what we need for dragging and dropping files.

                  We also check to see if the window has `FormData` and `FileReader` objects
                  present so we can do our AJAX uploading
                */
                return ( ( 'draggable' in div )
                    || ( 'ondragstart' in div && 'ondrop' in div ) )
                    && 'FormData' in window
                    && 'FileReader' in window;
            },
                        /*
              Gets the image preview for the file.
            */
            getImagePreviews(){
                /*
                  Iterate over all of the files and generate an image preview for each one.
                */
         
        
                if(!this.multiple){
                
                    if ( /\.(jpe?g|png|gif)$/i.test( this.file.name ) ) {
                        let reader = new FileReader();

                        reader.addEventListener("load", function(){
                            this.$refs['preview-file'].src = reader.result;
                        }.bind(this), false);

                        reader.readAsDataURL( this.file );
                    }
                    else{
                        /*
                          We do the next tick so the reference is bound and we can access it.
                        */
                        this.$nextTick(function(){
                            this.$refs['preview'+parseInt( i )][0].src = '/images/file-regular.svg';
                        });
                    }
                }
                
                for( let i = 0; i < this.files.length; i++ ){
                    /*
                      Ensure the file is an image file
                    */
                
                   
                    if ( /\.(jpe?g|png|gif)$/i.test( this.files[i].name ) ) {
                        /*
                          Create a new FileReader object
                        */
                        let reader = new FileReader();

                        /*
                          Add an event listener for when the file has been loaded
                          to update the src on the file preview.
                        */
                        reader.addEventListener("load", function(){
                            this.$refs['preview'+parseInt( i )][0].src = reader.result;
                        }.bind(this), false);

                        /*
                          Read the data for the file in through the reader. When it has
                          been loaded, we listen to the event propagated and set the image
                          src to what was loaded from the reader.
                        */
                        reader.readAsDataURL( this.files[i] );
                    }else{
                        /*
                          We do the next tick so the reference is bound and we can access it.
                        */
                        this.$nextTick(function(){
                            this.$refs['preview'+parseInt( i )][0].src = '/images/file-regular.svg';
                        });
                    }
                }
            },


            async submitFiles(data){
                /*
                  Initialize the form data
                */
               
                if(this.files.length){

                    let formData = new FormData();
                   
                   if(!this.multiple){
                        formData.append('file', this.file);
                    }else{
                        for( var i = 0; i < this.files.length; i++ ){
                            let file = this.files[i];
                            formData.append('files[' + i + ']', file);
                        }
                    }
                    

                    formData.append('id',data.id)
                    /*
                    Make the request to the POST /file-drag-drop URL
                    */
                    var self = this
                    await axios.post(this.uploadUrl,
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        },
                        {
                            onUploadProgress: function( progressEvent ) {
                                this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                            }.bind(this)
                        }
                    ).then(function(){
                        
                    })
                    .catch(function(){
                        console.log('FAILURE!!');
                    });

                   

                }
                this.files = []
               
                 eventBus.$emit('images-uploaded',data);
            },
        }
    }
</script>

<style scoped lang="scss">
    form{
        display: block;
        width: 50%;
        height: 9.5rem;
        background: #ccc;
        margin: auto;
        margin-top: 35px;
        text-align: center;
        line-height: 400px;
    }
    div.file-listing{
        width: 400px;
        margin: auto;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    div.file-listing img{
        height: 100px;
    }
    div.remove-container{
        text-align: center;
    }

    div.remove-container a{
        color: red;
        cursor: pointer;
    }

    a.submit-button{
        display: block;
        margin: auto;
        text-align: center;
        width: 200px;
        padding: 10px;
        text-transform: uppercase;
        background-color: #CCC;
        color: white;
        font-weight: bold;
        margin-top: 20px;
    }

    .drop-files{
        text-align: center;
        margin: auto;
        display: block;
        font-size: 12px;
        display: block;
        color: #7D92A3;
        cursor:pointer;
    }
    progress{
        width: 400px;
        margin: auto;
        display: block;
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .fa-times{
        cursor:pointer;
    }
</style>
