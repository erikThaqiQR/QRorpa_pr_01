

<style>
/* The switch - the box around the slider */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    /* float: right; */
}

/* Hide default HTML checkbox */
.switch input {
    display: none;
}

/* The slider */
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input.default:checked+.slider {
    background-color: #444;
}

input.primary:checked+.slider {
    background-color: #2196F3;
}

input.success:checked+.slider {
    background-color: #8bc34a;
}

input.info:checked+.slider {
    background-color: #3de0f5;
}

input.warning:checked+.slider {
    background-color: #FFC107;
}

input.danger:checked+.slider {
    background-color: #f44336;
}

input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.scrolling {
    /* Note #2 */
    position: fixed;
    top: 0px;
}



.noBorder:active {
    outline: none;
}

.noBorder:focus {
    outline: none;
    box-shadow: none;
}





input[type="number"]:disabled {
    background-color: white;
}

.btn:focus,
.btn:active {
    outline: none !important;
    box-shadow: none;
}


.MultiCarousel {
    float: left;
    overflow: hidden;
    padding: 5px;
    width: 100%;
    position: relative;
}

.MultiCarousel .MultiCarousel-inner {
    transition: 1s ease all;
    float: left;
}

.MultiCarousel .MultiCarousel-inner .item {
    float: left;
}

.MultiCarousel .MultiCarousel-inner .item>div {
    text-align: center;
    padding-left: 50px;
    padding-right: 50px;
    padding: 3px;
    margin: 5px;
    background: #f1f1f1;
    color: #666;
}

.MultiCarousel .leftLst,
.MultiCarousel .rightLst {
    position: absolute;
    border-radius: 50%;
    top: calc(50% - 20px);
}

.MultiCarousel .leftLst {
    left: 0;
}

.MultiCarousel .rightLst {
    right: 0;
}

.MultiCarousel .leftLst.over,
.MultiCarousel .rightLst.over {
    pointer-events: none;
    background: #ccc;
}


.hover-pointer:hover {
    cursor: pointer;
}





#searchBar {
    background-image: url('../storage/icons/search.png');
    /* Add a search icon to input */
    background-size: 25px 25px;
    background-position: 10px 12px;
    /* Position the search icon */
    background-repeat: no-repeat;
    /* Do not repeat the icon image */
    width: 100%;
    /* Full-width */
    font-size: 16px;
    /* Increase font-size */
    padding: 12px 20px 12px 40px;
    /* Add some padding */
    border: 1px solid #000;
    /* Add a grey border */
    margin-bottom: 12px;
    /* Add some space below the input */
    opacity: 0.45;
    border-radius: 20px;
}




    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        text-indent: 1px;
        text-overflow: '';
    }



    .swiper-container{
        background-color:#FFF;
    padding-top: 5px !important;
    }
    .swiper-slide{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .swiper-slide img{
        object-fit:cover;

    }
    .swiper-slide p{
        margin-top:5px;
        margin:0;
    }
    .rec .teksti{
        margin:0 auto;
        margin-bottom:10px;
    }
    .rec{
        margin-bottom:10px;
    }



    .color-text{
    color:rgb(72, 81, 87);
    }



        @media (max-width: 375px) {
            .emriRec {
                margin-top: -17px;
                margin-bottom: -30px;

            }

            .recProElement {
                height: auto;
                width: 100px;

            }
        }

        @media (max-width:420px) and (min-width:376px) {
            .emriRec {
                margin-top: -16px;
                margin-bottom: -30px;
            }

            .recProElement {
                height: auto;
                width: 100px;

            }
        }

        @media (max-width:600px) and (min-width:421px) {
            .emriRec {
                margin-top: -16px;
                margin-bottom: -30px;
            }

            .recProElement {
                height: auto;
                width: 100px;

            }
        }

        @media (max-width:2400px) and (min-width:601px) {
            .emriRec {
                margin-top: -16px;
            }

            .recProElement {
                height: auto;
                width: 90px;
            }
        }
        


     
    .teksti{
        justify-content:space-between;
        margin-top:-50px;
        color:#FFF;
        font-weight:bold;
        font-size:23px;
        margin-bottom:10px;
    }
    
    .prod-name{
        line-height: 2;
    }
    .add-plus-section{
        text-align: right;
        padding: 0px;
    }
    .product-section{
        border-bottom: 1px solid #dcd9d9;
        padding-bottom: 15px;
    }
    .recommended-title{
        margin-left: 0px !important;
    }
    .teksti strong{
        margin-left:20px;
    }
    .teksti i{
        margin-right:20px
    }









    .footerPhone {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: rgb(39, 190, 175);
        color: white;
        text-align: center;
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 19px;
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        z-index: 1000;
    }

    #anchorOrder {
        color: white;
        font-size: 19px;
    }

    a.disabled {

        cursor: not-allowed;
        pointer-events: none;
    }


    .checkoutBtn {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: rgb(39, 190, 175);
        color: white;
        text-align: center;
        padding: 10px;
        font-size: 19px;
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        z-index: 1000;
    }

    /*Arbnor CSS */
    .searchBar-section{
            display: inline-block;
        }
        .center-section-menu{
            margin:15px;
        }
    .slideshow-container{
        width: 100%;
        height: all;
    }
    .search-area{
        display: inline-block;
    }
    .allKatFoto img{
        object-fit: cover;          
    }
    .search{
        position: absolute;
    }
    .restaurant-description{
        font-size: 14px;
        background: #f5f8fa;
        padding: 10px;
    }
    .info-table td{
        padding: 8px;
    }
    .search{
        width: 100%;
        margin-left: 0;
    }
    @media only screen and (min-width: 768px) {

        .profilepic-area{
            text-align: center;
        }
        .cover-container{
            max-width: 100%;
        }
        .searchBar-section{
            display: inline-block;
        }
        .search{
            width: 100%;
            margin-left: 0%;
        }
        /*.left-section, .center-section, .right-section{
            display: inline;
            float: left;        
        }*/
        .swiper-container.col-lg-6.swiper-initialized.swiper-horizontal{
            max-width: 100%;
        }
        /*.left-section, .right-section{
            padding: 15px;
            background: white;
        }*/
            .center-section{
                box-sizing: border-box;
                position: relative;
                top: 20px;
                margin: 0 auto;
                padding-left: 80px;
                    padding-right: 80px;
            }
            .left-section {
                box-sizing: border-box;
                position: fixed;
                background: #fff;
                top: 0%;
                padding-top: 20px;
                height: 100vh;
                overflow-y: scroll;
            }
            .right-section {
            box-sizing: border-box;
            position: fixed;
            right: 0%;
            background: #fff;
            top: 0%;
            padding-top: 20px;
            height: 100vh;
            overflow-y: scroll;
            }
            nav#navDesktop, .cover-container{
                width: 50%;
                margin-left: 25%;
            }
            nav#navDesktop {
                position: fixed;
                z-index: 999;
                width: 50%;
                margin-left: 25%;
            }
            .slideshow-container {
                margin-top: 65px;
                padding-left: 80px;
                padding-right: 80px;
            }
            .mySlides.fadeSlide img{
                height: 200px !important;
            }
            .leftSide-kat, .rightSide-kat{
                display: none;
            }
            .restaurant-title{
                padding-left: 80px;
                padding-right: 80px;
            }
            .profilepic-area img{
                width: 130px !important;
                height: 130px !important;
            }
            .allKatFoto img{
                height: 175px !important;           
            }
            .right-section h4{
                background: #27beaf;
                padding: 10px;
                color: #fff;
            }
            .right-section i{
                color: #ffb101;
            }
            .ratings-table{
                overflow-y: scroll;
            height: 400px;
            display: block;
            }

            .profilepic-area{
                margin-top:-50px;
                z-index: 1;
            }
            #ajaxform{
                display: none;
            }
            .infoButton{
                display: none;
            }
            .recommended-mobile{
                display: none;
            }
    }

    @media (min-width: 768px) and (max-width: 1023px){
    .mySlides.fadeSlide img{
                height: 130px !important;
            }
        }
    @media only screen and (min-width: 1024px){
            .swiper-slide.recProElement img{
                width: 100px !important;
                height: 100px !important;
            }
    }
    @media (min-width: 768px) and (max-width: 1023px){
            .swiper-slide.recProElement img{
                width: 100px !important;
                height: 100px !important;
            }
    }
    @media only screen and (max-width: 767px) {

        .left-section{
            display: none;
        }
        .right-section{
            display: none;
        }
        .swiper-container {
            padding-left: 0px;
            padding-right: 0px;
        }
        .recommended-desktop{
            display: none;
        }
    }

    @media only screen and (max-width: 414px) {

        .swiper-slide.recProElement {
            margin-right: -2px !important;
        }
    }
    @media only screen and (max-width: 375px) {

        .swiper-slide.recProElement {
            margin-right: 1px !important;
        }
    }



    @media (max-width: 375px) {
        .emriRec {
            margin-top: -17px;
            margin-bottom: -30px;
        }
        .recProElement {
            height: auto;
            width: 100px;
        }
    }
    @media (max-width:420px) and (min-width:376px) {
        .emriRec {
            margin-top: -16px;
            margin-bottom: -30px;
        }
        .recProElement {
            height: auto;
            width: 100px;
        }
    }
    @media (max-width:600px) and (min-width:421px) {
        .emriRec {
            margin-top: -16px;
            margin-bottom: -30px;
        }
        .recProElement {
            height: auto;
            width: 100px;
        }
    }
    @media (max-width:2400px) and (min-width:601px) {
        .emriRec {
            margin-top: -16px;
        }
        .recProElement {
            height: auto;
            width: 90px;
        }
    }
    
    /*End Arbnor CSS*/

</style>
