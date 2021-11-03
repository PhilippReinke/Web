
// Seitenleiste einfahren und ausfahren
function openNav() 
{
	document.getElementById('sidenav').style.width = '250px';
	if(window.innerWidth >= 650)
	{
        document.body.style.marginLeft = '250px';
	}
}

function closeNav() 
{
	document.getElementById('sidenav').style.width = '0px';
    document.body.style.marginLeft = '0px';
}


// Datei hochladen
function fileChange()
{
	// Progressbar sichtbar machen
	var prog = document.getElementById('progress');
	prog.style.visibility = 'visible';
	
    //FileList Objekt aus dem Input Element mit der ID 'fileA'
    var fileList = document.getElementById('fileA').files;
 
    //File Objekt (erstes Element der FileList)
    var file = fileList[0];
 
    //File Objekt nicht vorhanden = keine Datei ausgewählt oder vom Browser nicht unterstützt
    if(!file)
        return;
		
    document.getElementById('progress').value = 0;
    document.getElementById('prozent').innerHTML = '0%';
}
 
var client = null;
 
function uploadFile()
{
    // Wieder unser File Objekt.
    var file = document.getElementById('fileA').files[0];
    //FormData Objekt erzeugen
    var formData = new FormData();
    // XMLHttpRequest Objekt erzeugen.
       client = new XMLHttpRequest();
 
    var prog = document.getElementById('progress');
 
    if(!file)
        return;
 
    prog.value = 0;
    prog.max = 100;
 
    // Fügt dem formData Objekt unser File Objekt hinzu.
    formData.append('datei', file);
 
    client.onerror = function(e) {
        alert('onError');
    };
 
    client.onload = function(e) {
        document.getElementById('prozent').innerHTML = '100%';
        prog.value = prog.max;
		
		location.reload();
    };
 
    client.upload.onprogress = function(e) {
        var p = Math.round(100 / e.total * e.loaded);
        document.getElementById('progress').value = p;            
        document.getElementById('prozent').innerHTML = p + '%';
    };
 
    client.onabort = function(e) {
        alert('Upload abgebrochen');
    };
 
    client.open('POST', '/js_php/upload.php');
    client.send(formData);
}
 
function uploadAbort() 
{
    if(client instanceof XMLHttpRequest)
	{
		client.abort();
		location.reload();
	}
}


// ...