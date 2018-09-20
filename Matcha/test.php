<html>
    <head>
        <style type='text/css'>
            span {
                text-decoration:underline;
                color:blue;
                cursor:pointer;
            }
        </style>
        <script>
            // show the given page, hide the rest
            function show(elementID) {
                // try to find the requested page and alert if it's not found
                var ele = document.getElementById(elementID);
                if (!ele) {
                    alert("no such element");
                    return;
                }

                // get all pages, loop through them and hide them
                var pages = document.getElementsByClassName('page');
                for(var i = 0; i < pages.length; i++) {
                    pages[i].style.display = 'none';
                }

                // then show the requested page
                ele.style.display = 'block';
            }
        </script>
    </head>
    <body>
      <p>
        Show page 
            <span onclick="show('Page1');">1</span>, 
            <span onclick="show('Page2');">2</span>, 
            <span onclick="show('Page3');">3</span>.
        </p>

    <div id="Page1" class="page" style="">
        Content of page 1
    </div>
    <div id="Page2" class="page" style="display:none">
        Content of page 2
    </div>
    <div id="Page3" class="page" style="display:none">
        Content of page 3
    </div>

    </body>
</html>