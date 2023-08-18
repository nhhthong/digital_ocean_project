document.addEventListener('DOMContentLoaded', function() {
        var width_1= 550;
    var height_1 = 733;
    var minWidth_1= 315;
    var minHeight_1 = 420;
     $(window).width(function(){
          var win = $(this); //this = window
          if (win.width() < 820) {
                width_1=320;
                height_1=220;
                minWidth_1=320;
                minHeight_1=220;
          }
        });
    const pageFlip = new St.PageFlip(
        document.getElementById("demoBookExample"),
        {
            width: width_1, // base page width
            height: height_1, // base page height
            size: "stretch",
            display:"single",
            usePortrait:true,
            // set threshold values:
            minWidth: minWidth_1,
            maxWidth: 1000,
            minHeight: minHeight_1,
            maxHeight: 1350,
            maxShadowOpacity: 0.5, // Half shadow intensity
            showCover: true,
            mobileScrollSupport: false // disable content scrolling on mobile devices
        }
    );
    //tien-test
    //     $(window).width(function(){
    //       var win = $(this); //this = window
    //       if (win.width() < 820) {
    //         console.log("mobile1");
    //       }
    //     });
    //     $(window).resize(function(){
    //     var win = $(this); //this = window
    //     if (win.width() < 820) {
    //         console.log("mobile");
    //       }
    //     });
    // //
    // load pages
    pageFlip.loadFromHTML(document.querySelectorAll(".page_1"));
   // document.querySelector(".page-total").innerText = pageFlip.getPageCount();
    // document.querySelector(
    //     ".page-orientation"
    // ).innerText = pageFlip.getOrientation();

    // document.querySelector(".btn-prev").addEventListener("click", () => {
    //     pageFlip.flipPrev(); // Turn to the previous page (with animation)
    // });

    // document.querySelector(".btn-next").addEventListener("click", () => {
    //     pageFlip.flipNext(); // Turn to the next page (with animation)
    // });

    // // triggered by page turning
    // pageFlip.on("flip", (e) => {
    //     document.querySelector(".page-current").innerText = e.data + 1;
    // });

    // // triggered when the state of the book changes
    // pageFlip.on("changeState", (e) => {
    //     document.querySelector(".page-state").innerText = e.data;
    // });

    // // triggered when page orientation changes
    // pageFlip.on("changeOrientation", (e) => {
    //     document.querySelector(".page-orientation").innerText = e.data;
    // });
});