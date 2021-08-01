$(document).ready(function()
{
    Notiflix.Confirm.Init({okButtonBackground:"#f05f5a",rtl:true,});

    Notiflix.Notify.Init(
    {
        fontFamily:"Quicksand",
        useGoogleFont:true,
        rtl:true,
        useFontAwesome:true,
        position:"right-bottom",
    });

    Notiflix.Report.Init({fontFamily:"Quicksand",useGoogleFont:true,rtl:true,backOverlay:false,});
})