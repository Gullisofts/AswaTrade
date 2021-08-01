<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <div class="container-fluid">
            <div class="dash_bod dash_bod_search">
                <h5 class="main_heading">إدارة المراجعات</h5>

                <div class="refresh_comments">
                    <button class="btn btn-primary" id="refresh_comments_st">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>

                <div class="mt-3">
                    <div class="search-sections search-comments" id="search-text">
                        <div class="search-div-content pt-2 pb-2">
                            <label>البحث بالنص</label>
                            <div class="form-group row mb-0">
                                <input type="text" id="searchbytextvalue" class="form-control col-8" style="border-radius: .0rem .25rem .25rem .0rem;" placeholder="نص المراجعة">
                                <a id="searchbytextst" class="btn btn-primary text-light search_btn_st"  style="cursor:pointer; border-radius: .25rem .0rem .0rem .25rem;">بحث</a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="show_search_res mt-3 res_bod_comments" id="res_bod">

                </div>

                <template v-if="allshown == 'false'">
                    <div class="mt-3 text-center w-100 mb-2">
                        <button id="show_more_cust_btn" class="btn btn-primary show_more text-light" style="cursor:pointer;">عرض المزيد...</button>
                    </div>
                </template>
                <template v-if="allshown == 'true'">
                    <div class="mt-3 text-center w-100 mb-2">
                        <button class="btn btn-primary show_more text-light" style="cursor:pointer;" disabled>نهاية النتائج</button>
                    </div>
                </template>
            </div>
        </div>
    </div>  
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import axios from "axios";

export default {

    components: {
        Dashsidebar,
        axios,
    },
    data() 
    {
        return {

            lastel: 0,
            allshown: "notset",
            searchval: "",
            searchmod: 0,
        }
    },
    mounted:function()
    {
        let self = this;
        let lastelement = self.lastel;
        let datagotten = "";
        let searchval = "";
        
        //Get Unmanaged Comments
        axios.post("/getunmanagedcomments", {lastelement})
        .then(function(response)
        {
            $("#res_bod").html("");

            if(response.data == "nodata")
            {
                $(document).ready(function()
                {
                    $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                });

                self.allshown = "true";
                document.getElementById("spinner_cont").style.display="none";
            }
            else if(typeof(response.data[1].data[0]) == "object" && response.data.length == 2)
            {
                self.allshown = "false";
                self.lastel = response.data[0];
                datagotten = response.data[1].data;

                for(let i=0; i<datagotten.length; i++)
                {
                    $("#res_bod").append('<ul class="list-group pr-0"><li class="list-group-item" style="background: #ecf0f1"><span class="font-weight-bold">معرف العميل:</span><span class="mr-2">'+datagotten[i].user_id+'</span></li><li class="list-group-item"><span class="font-weight-bold">الاسم:</span><span class="mr-2">'+datagotten[i].user_name+'</span></li><li class="list-group-item"><span class="font-weight-bold">نص المراجعة:</span><textarea class="form-control mt-2">'+datagotten[i].content+'</textarea></li><li class="list-group-item text-center comment_manage_opts"><i id="'+datagotten[i].id+'" class="fas fa-check-circle appr_comment text-success"></i><i id="'+datagotten[i].id+'" class="fas fa-times-circle dest_comment text-danger mr-5"></i></li></ul>');
                }

                document.getElementById("spinner_cont").style.display="none";
                
            }
            else if(typeof(response.data[1].data[0]) == "object" && response.data.length == 3)
            {
                self.allshown = "true";
                self.lastel = response.data[0];
                datagotten = response.data[1].data;

                for(let i=0; i<datagotten.length; i++)
                {
                    $("#res_bod").append('<ul class="list-group pr-0"><li class="list-group-item" style="background: #ecf0f1"><span class="font-weight-bold">معرف العميل:</span><span class="mr-2">'+datagotten[i].user_id+'</span></li><li class="list-group-item"><span class="font-weight-bold">الاسم:</span><span class="mr-2">'+datagotten[i].user_name+'</span></li><li class="list-group-item"><span class="font-weight-bold">نص المراجعة:</span><textarea class="form-control mt-2">'+datagotten[i].content+'</textarea></li><li class="list-group-item text-center comment_manage_opts"><i id="'+datagotten[i].id+'" class="fas fa-check-circle appr_comment text-success"></i><i id="'+datagotten[i].id+'" class="fas fa-times-circle dest_comment text-danger mr-5"></i></li></ul>');
                }

                document.getElementById("spinner_cont").style.display="none";

            }

        })
        .catch(function(error)
        {

        });


        //Show More Button Click
        $(document).on("click", "#show_more_cust_btn", function(event)
        {
            if(self.searchmod == 0)
            {
                event.preventDefault();
                document.getElementById("spinner_cont").style.display="block";

                let lastelement = self.lastel;

                axios.post("/getunmanagedcomments", {lastelement})
                .then(function(response)
                {
                    if(response.data == "nodata")
                    {
                        $(document).ready(function()
                        {
                            $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                        });

                        self.allshown = "true";
                        document.getElementById("spinner_cont").style.display="none";
                    }
                    else if(typeof(response.data[1].data[0]) == "object" && response.data.length == 2)
                    {
                        self.allshown = "false";
                        self.lastel = response.data[0];
                        datagotten = response.data[1].data;

                        for(let i=0; i<datagotten.length; i++)
                        {
                            $("#res_bod").append('<ul class="list-group pr-0"><li class="list-group-item" style="background: #ecf0f1"><span class="font-weight-bold">معرف العميل:</span><span class="mr-2">'+datagotten[i].user_id+'</span></li><li class="list-group-item"><span class="font-weight-bold">الاسم:</span><span class="mr-2">'+datagotten[i].user_name+'</span></li><li class="list-group-item"><span class="font-weight-bold">نص المراجعة:</span><textarea class="form-control mt-2">'+datagotten[i].content+'</textarea></li><li class="list-group-item text-center comment_manage_opts"><i id="'+datagotten[i].id+'" class="fas fa-check-circle appr_comment text-success"></i><i id="'+datagotten[i].id+'" class="fas fa-times-circle dest_comment text-danger mr-5"></i></li></ul>');
                        }

                        document.getElementById("spinner_cont").style.display="none";
                        
                    }
                    else if(typeof(response.data[1].data[0]) == "object" && response.data.length == 3)
                    {
                        self.allshown = "true";
                        self.lastel = response.data[0];
                        datagotten = response.data[1].data;

                        for(let i=0; i<datagotten.length; i++)
                        {
                            $("#res_bod").append('<ul class="list-group pr-0"><li class="list-group-item" style="background: #ecf0f1"><span class="font-weight-bold">معرف العميل:</span><span class="mr-2">'+datagotten[i].user_id+'</span></li><li class="list-group-item"><span class="font-weight-bold">الاسم:</span><span class="mr-2">'+datagotten[i].user_name+'</span></li><li class="list-group-item"><span class="font-weight-bold">نص المراجعة:</span><textarea class="form-control mt-2">'+datagotten[i].content+'</textarea></li><li class="list-group-item text-center comment_manage_opts"><i id="'+datagotten[i].id+'" class="fas fa-check-circle appr_comment text-success"></i><i id="'+datagotten[i].id+'" class="fas fa-times-circle dest_comment text-danger mr-5"></i></li></ul>');
                        }

                        document.getElementById("spinner_cont").style.display="none";

                    }

                })
                .catch(function(error)
                {

                });
            }

        });


        //Search Comment
        $(document).on("click", ".search_btn_st", function()
        {
            document.getElementById("spinner_cont").style.display="block";
            self.searchmod = 1;
            self.lastel = 0;
            lastelement = self.lastel;
            self.allshown = "notset";
            self.searchval = $("#searchbytextvalue").val();
            searchval = self.searchval;
            $("#res_bod").html("");
                
            axios.post("/searchcommentsbytext", {searchval, lastelement})
            .then(function(response)
            {
                if(response.data == "nodata")
                {
                    $(document).ready(function()
                    {
                        $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                    });

                    self.allshown = "true";
                    document.getElementById("spinner_cont").style.display="none";
                }
                else if(typeof(response.data[1].data[0]) == "object" && response.data.length == 2)
                {
                    self.allshown = "false";
                    self.lastel = response.data[0];
                    datagotten = response.data[1].data;

                    for(let i=0; i<datagotten.length; i++)
                    {
                        $("#res_bod").append('<ul class="list-group pr-0"><li class="list-group-item" style="background: #ecf0f1"><span class="font-weight-bold">معرف العميل:</span><span class="mr-2">'+datagotten[i].user_id+'</span></li><li class="list-group-item"><span class="font-weight-bold">الاسم:</span><span class="mr-2">'+datagotten[i].user_name+'</span></li><li class="list-group-item"><span class="font-weight-bold">نص المراجعة:</span><textarea class="form-control mt-2">'+datagotten[i].content+'</textarea></li><li class="list-group-item text-center comment_manage_opts"><i id="'+datagotten[i].id+'" class="fas fa-check-circle appr_comment text-success"></i><i id="'+datagotten[i].id+'" class="fas fa-times-circle dest_comment text-danger mr-5"></i></li></ul>');
                    }

                    document.getElementById("spinner_cont").style.display="none";
                    
                }
                else if(typeof(response.data[1].data[0]) == "object" && response.data.length == 3)
                {
                    self.allshown = "true";
                    self.lastel = response.data[0];
                    datagotten = response.data[1].data;

                    for(let i=0; i<datagotten.length; i++)
                    {
                        $("#res_bod").append('<ul class="list-group pr-0"><li class="list-group-item" style="background: #ecf0f1"><span class="font-weight-bold">معرف العميل:</span><span class="mr-2">'+datagotten[i].user_id+'</span></li><li class="list-group-item"><span class="font-weight-bold">الاسم:</span><span class="mr-2">'+datagotten[i].user_name+'</span></li><li class="list-group-item"><span class="font-weight-bold">نص المراجعة:</span><textarea class="form-control mt-2">'+datagotten[i].content+'</textarea></li><li class="list-group-item text-center comment_manage_opts"><i id="'+datagotten[i].id+'" class="fas fa-check-circle appr_comment text-success"></i><i id="'+datagotten[i].id+'" class="fas fa-times-circle dest_comment text-danger mr-5"></i></li></ul>');
                    }

                    document.getElementById("spinner_cont").style.display="none";

                }

            })
            .catch(function(error)
            {

            });

            

        });

        //Search Comment => Show More
        $(document).on("click", "#show_more_cust_btn", function()
        {
            if(self.searchmod == 1)
            {
                document.getElementById("spinner_cont").style.display="block";

                lastelement = self.lastel;
                searchval = self.searchval;
                    
                axios.post("/searchcommentsbytext", {searchval, lastelement})
                .then(function(response)
                {
                    if(response.data == "nodata")
                    {
                        $(document).ready(function()
                        {
                            $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                        });

                        self.allshown = "true";
                        document.getElementById("spinner_cont").style.display="none";
                    }
                    else if(typeof(response.data[1].data[0]) == "object" && response.data.length == 2)
                    {
                        self.allshown = "false";
                        self.lastel = response.data[0];
                        datagotten = response.data[1].data;

                        for(let i=0; i<datagotten.length; i++)
                        {
                            $("#res_bod").append('<ul class="list-group pr-0"><li class="list-group-item" style="background: #ecf0f1"><span class="font-weight-bold">معرف العميل:</span><span class="mr-2">'+datagotten[i].user_id+'</span></li><li class="list-group-item"><span class="font-weight-bold">الاسم:</span><span class="mr-2">'+datagotten[i].user_name+'</span></li><li class="list-group-item"><span class="font-weight-bold">نص المراجعة:</span><textarea class="form-control mt-2">'+datagotten[i].content+'</textarea></li><li class="list-group-item text-center comment_manage_opts"><i id="'+datagotten[i].id+'" class="fas fa-check-circle appr_comment text-success"></i><i id="'+datagotten[i].id+'" class="fas fa-times-circle dest_comment text-danger mr-5"></i></li></ul>');
                        }

                        document.getElementById("spinner_cont").style.display="none";
                        
                    }
                    else if(typeof(response.data[1].data[0]) == "object" && response.data.length == 3)
                    {
                        self.allshown = "true";
                        self.lastel = response.data[0];
                        datagotten = response.data[1].data;

                        for(let i=0; i<datagotten.length; i++)
                        {
                            $("#res_bod").append('<ul class="list-group pr-0"><li class="list-group-item" style="background: #ecf0f1"><span class="font-weight-bold">معرف العميل:</span><span class="mr-2">'+datagotten[i].user_id+'</span></li><li class="list-group-item"><span class="font-weight-bold">الاسم:</span><span class="mr-2">'+datagotten[i].user_name+'</span></li><li class="list-group-item"><span class="font-weight-bold">نص المراجعة:</span><textarea class="form-control mt-2">'+datagotten[i].content+'</textarea></li><li class="list-group-item text-center comment_manage_opts"><i id="'+datagotten[i].id+'" class="fas fa-check-circle appr_comment text-success"></i><i id="'+datagotten[i].id+'" class="fas fa-times-circle dest_comment text-danger mr-5"></i></li></ul>');
                        }

                        document.getElementById("spinner_cont").style.display="none";

                    }

                })
                .catch(function(error)
                {

                });
            }
            
        });

        //Approve Comment
        $(document).on("click", ".appr_comment", function()
        {
            document.getElementById("spinner_cont").style.display="block";

            let appr_id = $(this).attr("ID");
            let el = $(this).parents("ul");

            axios.get("/approvecomment/"+appr_id)
            .then(function(response)
            {
                el.fadeOut();
                document.getElementById("spinner_cont").style.display="none";
            })
            .catch(function(error)
            {
                
            });
        });

        //Destroy Comment 
        $(document).on("click", ".dest_comment", function()
        {
            document.getElementById("spinner_cont").style.display="block";

            let dest_id = $(this).attr("ID");
            let el = $(this).parents("ul");

            axios.get("/destroycomment/"+dest_id)
            .then(function(response)
            {
                el.fadeOut();

                document.getElementById("spinner_cont").style.display="none";
            })
            .catch(function(error)
            {
                
            });
        });

        //Refresh Comments
        $(document).on("click", "#refresh_comments_st", function()
        {
            window.location.reload();
        });
    },

}
</script>
