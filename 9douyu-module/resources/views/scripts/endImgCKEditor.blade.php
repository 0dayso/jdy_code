      <!--使用简版CKEditor-->
      <script src="{{ assetUrlByCdn('theme/metro/ckeditor/ckeditor.js') }}"></script>
      <script>
        CKEDITOR.replace( '{{$id}}', {
          "extraPlugins": "imgbrowse",
          "toolbar" :  [
            { name: 'insert', items: [ 'Image' ] },
            { name: 'others', items: [ '-' ] },
            { name: 'about', items: [ 'About' ] }
          ]
        });
      </script>
