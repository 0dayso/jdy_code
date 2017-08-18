
<!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <script type="text/javascript" src="static/weixin/js/pdfobject.js" src="./static/weixin/js"></script>
        <style>
            body , html{
                background-color: #404040;
                height: 100%;
                padding: 0;
                margin: 0;
            }
        </style>
    </head>
    <body>
    <frame src="{{$pdfUrl}}"></frame>
    <script type="text/javascript">
        window.onload = function (){
            var success = new PDFObject({ url: "{{$pdfUrl}}" }).embed();

            console.log(success);
        };
    </script>
    </body>
    </html>