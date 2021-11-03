const root = {
	data()
	{
		return {
			dragAndDropCapable: false,			
			hiddenInput: null,
			files: {}, nextUniqueId: 0,
			server_address: "https://heroku-grayscale-converter.herokuapp.com/",
		};
	},
	mounted()
	{
		// determine if drag and drop is supported
		this.dragAndDropCapable = this.determineDragAndDropCapable();

		// create "hidden" input element
		this.hiddenInput = document.createElement("input");
		this.hiddenInput.type = "file";
		this.hiddenInput.multiple = "multiple";
		this.hiddenInput.onchange = function(e) // same as for drop event
		{
			// capture the files from the drop event and add them to our local files array
			for(let i=0; i<e.target.files.length; i++) { this.addFile(e.target.files[i]); } 
		}.bind(this);

		// bind events to elements
		if(this.dragAndDropCapable)
		{
			// listen to all of the drag events and bind an
			// event listener to each for the fileform
			["drag", "dragstart", "dragend", "dragover", "dragenter", "dragleave", "drop"].forEach(function(evt)
			{
				// disable default behaviour
				this.$refs.uploadArea.addEventListener(evt, function(e)
				{
					e.preventDefault();
					e.stopPropagation();
				}.bind(this), false);
			}.bind(this));
			// add an event listener for drop to the form
			this.$refs.uploadArea.addEventListener("drop", function(e)
			{
				// capture the files from the drop event and add them to our local files array
				for(let i=0; i<e.dataTransfer.files.length; i++) { this.addFile(e.dataTransfer.files[i]); }
			}.bind(this));
			// dropzone change colour when when dragover
			["dragenter", "dragover"].forEach(event => this.$refs.uploadArea.addEventListener(event, function(e)
			{
				this.$refs.uploadArea.style.background = "#faf9f9";
				this.$refs.uploadArea.style.border = "2px #2275d7 dashed";
			}.bind(this)));
			["dragleave", "drop"].forEach(event => this.$refs.uploadArea.addEventListener(event, function(e)
			{
				this.$refs.uploadArea.style.background = "white";
				this.$refs.uploadArea.style.border = "2px #aaa dashed";
			}.bind(this)));
		}
	},
	methods:
	{
		addFile(file)
		{
			// ToDo: check File type
			// add file and increase unique id counter
			this.files[this.nextUniqueId] = {uploadPercentage:0, previewSrc:URL.createObjectURL(file), data:file, downloadLink:"#", error:"", status:""};
			this.nextUniqueId++;
			// start uploading
			this.submitFile(this.nextUniqueId-1);
		},
		determineDragAndDropCapable()
		{
			// create test element to see if certain events are present
			let div = document.createElement("div");
			return (("draggable" in div) || ("ondragstart" in div && "ondrop" in div)) && "FormData" in window && "FileReader" in window;
		},
		removeFile(id)
		{
			delete this.files[id];
		},
		submitFile(id)
		{
			// initialise the form data and add file
			let formData = new FormData();
			formData.append("files["+0+"]", this.files[id].data);
			// show processing animation
			//...
			// make the request to the POST file-drag-drop URL
			axios.post(this.server_address, formData, {
				headers:
				{
					"Content-Type": "multipart/form-data",
				},
				onUploadProgress: function(progressEvent)
				{
					this.files[id].uploadPercentage = parseInt(Math.round((progressEvent.loaded*100)/progressEvent.total));
				}.bind(this)
			}).then(function(response)
			{
				// SUCCESS
				for (let key of Object.keys(response.data))
				{
					// show symbol
					this.files[id].status = response.data[key].status;
					// download link is available
					if (response.data[key].status == "ok")
					{
						this.files[id].downloadLink = response.data[key].message;
					}
					// error occured
					else
					{
						this.files[id].error = response.data[key].message;
					}
				}
			}.bind(this)).catch(function(response)
			{
				// ERROR
				this.files = {};
				alert("Fehler: Der Server ist zurzeit außer Dienst. Bitte versuchen Sie es später erneut.");
			}.bind(this));
		},
		openFileDialog()
		{
			this.hiddenInput.click();
		},
	},
};
const app = Vue.createApp(root).mount("#app");