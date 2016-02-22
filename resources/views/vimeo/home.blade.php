<html>
  <head>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <style>

    /* Sticky footer styles
    -------------------------------------------------- */
    html {
      position: relative;
      min-height: 100%;
    }
    body {
      /* Margin bottom by footer height */
      margin-bottom: 60px;
    }
    .footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      /* Set the fixed height of the footer here */
      height: 60px;
      background-color: #f5f5f5;
    }

    /* Custom page CSS
    -------------------------------------------------- */
    /* Not required for template or sticky footer method. */

    .container {
      width: auto;
      max-width: 680px;
      padding: 0 15px;
    }
    .container .text-muted {
      margin: 20px 0;
    }

    #drop_zone {
      border: 2px dashed #bbb;
      -moz-border-radius: 5px;
      -webkit-border-radius: 5px;
      border-radius: 5px;
      padding: 25px;
      text-align: center;
      font: 20pt bold 'Helvetica';
      color: #bbb;
    }

    p#video-data{
      margin-top: 1em;
      font-size: 1.1em;
      font-weight: 500;
    }

    </style>

  <script async defer id="github-bjs" src="https://buttons.github.io/buttons.js"></script>

  </head>
  <body>

    <div class="container">
      <div class="page-header">
        <h1><img src="img/icon.png" style="width:50px"></img>&nbsp;<span style="color:#F3615E;">Laravel</span> Vimeo Upload</h1>

        <a class="github-button" href="https://github.com/andrestntx/laravel-vimeo-upload" data-icon="octicon-star" data-style="mega" data-count-href="/andrestntx/laravel-vimeo-upload/stargazers" data-count-api="/repos/andrestntx/laravel-vimeo-upload#stargazers_count" data-count-aria-label="# stargazers on GitHub" aria-label="Star andrestntx/laravel-vimeo-upload on GitHub">Star</a>

        <!-- Place this tag where you want the button to render. -->
        <a class="github-button" href="https://github.com/andrestntx/laravel-vimeo-upload/fork" data-icon="octicon-repo-forked" data-style="mega" data-count-href="/andrestntx/laravel-vimeo-upload/network" data-count-api="/repos/andrestntx/laravel-vimeo-upload#forks_count" data-count-aria-label="# forks on GitHub" aria-label="Fork andrestntx/laravel-vimeo-upload on GitHub">Fork</a>

        <a class="github-button" href="https://github.com/andrestntx" data-style="mega" data-count-href="/andrestntx/followers" data-count-api="/users/andrestntx#followers" data-count-aria-label="# followers on GitHub" aria-label="Follow @andrestntx on GitHub">Follow @andrestntx</a>

      </div>
      <p class="lead">
        Drag you video file into the area below to upload to your vimeo account. Remember set
        the laravel file <b>config/vimeo.php</b> with your acces token
      </p>
      <div>
      <div class="checkbox">
        <label>
          <input type="checkbox" id="upgrade_to_1080" name="upgrade_to_1080"> Upgrade to 1080 </input>
        </label>
      </div>
      <p id="video-data">Video Data</p>
      <div class="form-group">
        <input type="text" name="name" id="videoName" class="form-control" placeholder="Name" value="default name"></input>
      </div>
      <div class="form-group">
        <input type="text" name="description" id="videoDescription" class="form-control" placeholder="Description" value = "default description"></input>
      </div>
      </div>
      <br>
      <div class="progress">
        <div id="progress" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
         0%
        </div>
      </div>
      <div id="drop_zone">Drop files here</div>
      <br>
      <div id="results"></div>
      <div class="api-useds">
        <h4>API's</h4>
        <ul>
          <li><a href="https://github.com/vinkla/vimeo">vinkla/vimeo (Laravel) By Vinkla</a></li>
          <li><a href="https://github.com/vimeo/vimeo.php">vimeo/vimeo.php By Vimeo</a></li>
          <li><a href="https://github.com/andrestntx/vimeo-upload">vimeo-upload by Websemantics</a></li>
          <li><a href="https://developer.vimeo.com/api/start">Oficial API Vimeo</a></li>
        </ul>
      </div>
    </div>

    <footer class="footer">
      <div class="container">
        <p class="text-muted"><a href="http://about.me/andrestntx">@andrestntx</a></p>
      </div>
    </footer>

     <script src="/js/upload.js"></script>
     <script type="text/javascript">
        videoData = {
          name: 'Default Name',
          description: 'Default Description'
        };

       /**
        * Called when files are dropped on to the drop target. For each file,
        * uploads the content to Drive & displays the results when complete.
        */
       function handleFileSelect(evt) {
         evt.stopPropagation();
         evt.preventDefault();
         var files = evt.dataTransfer.files; // FileList object.
         var upgrade_to_1080 = document.getElementById("upgrade_to_1080").checked;

         // Set Video Data
         videoData.name = document.getElementById("videoName").value;
         videoData.description = document.getElementById("videoDescription").value;

         // Clear the results div
         var node = document.getElementById('results');
         while (node.hasChildNodes()) node.removeChild(node.firstChild);

         // Rest the progress bar
         updateProgress(0);

         var uploader = new MediaUploader({
             file: files[0],
             upgrade_to_1080: upgrade_to_1080,
             videoData: videoData,
             url: '/request',
             complete_url: '/complete',
             update_video_data_url: '/update',
             onError: function(data) {

                var errorResponse = JSON.parse(data);
                message = errorResponse.error;

                var element = document.createElement("div");
                element.setAttribute('class', "alert alert-danger");
                element.appendChild(document.createTextNode(message));
                document.getElementById('results').appendChild(element);

             },
             onProgress: function(data) {
                updateProgress(data.loaded / data.total);
             },
             onComplete: function(videoId) {

                var url = "https://vimeo.com/"+videoId;

                var a = document.createElement('a');
                a.appendChild(document.createTextNode(url));
                a.setAttribute('href',url);

                var element = document.createElement("div");
                element.setAttribute('class', "alert alert-success");
                element.appendChild(a);

                document.getElementById('results').appendChild(element);
             }
         });
         uploader.upload();
       }

       /**
        * Dragover handler to set the drop effect.
        */
       function handleDragOver(evt) {
         evt.stopPropagation();
         evt.preventDefault();
         evt.dataTransfer.dropEffect = 'copy';
       }

       /**
        * Wire up drag & drop listeners once page loads
        */
       document.addEventListener('DOMContentLoaded', function () {
           var dropZone = document.getElementById('drop_zone');
           dropZone.addEventListener('dragover', handleDragOver, false);
           dropZone.addEventListener('drop', handleFileSelect, false);
       });
;
       /**
        * Updat progress bar.
        */
       function updateProgress(progress) {
          progress = Math.floor(progress * 100);
          var element = document.getElementById('progress');
          element.setAttribute('style', 'width:'+progress+'%');
          element.innerHTML = progress+'%';
       }


      progress
     </script>
  </body>
</html>
