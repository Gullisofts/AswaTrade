<template>
    <div>
        <Confirmation></Confirmation>
        <Popmessage></Popmessage>
        <Dashsidebar></Dashsidebar>
        <div class="container-fluid">
            <div class="dash_bod">
                <h5 class="main_heading">الأقسام</h5>

                <form class="main-gen_form" id="add-new-product">
                    <div class="sections_view">

                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Confirmation from "./../components/Confirmation.vue";
import Popmessage from "./../components/Popmessage.vue";
import axios from "axios";
export default {

    components: {

        Dashsidebar,
        axios,
        Confirmation,
        Popmessage,
    },
    data() 
    {
        return {

            sectionsids: [],

        }
    },
    mounted:function()
    {
        document.getElementById("spinner_cont").style.display="block";

        let self = this;

        //Hide/Show Section Details
        $(document).unbind('click').on("click", ".show_section_details", function()
        {
            $(this).closest(".section_view").children(".section_content").slideToggle();
        })

        //Get Sections
        axios.get("/getallsections")
        .then(function(response)
        {
            $(".sections_view").html("");

            self.sectionsids = response.data;
            
            for(let i=0; i<response.data.length; i++)
            {
                axios.get("/getsectiondata/"+response.data[i])
                .then(function(response)
                {
                    $(".sections_view").append('<div class="section_view"><h6 class="show_section_details"><span>'+response.data[0]+'</span><i class="fas fa-chevron-down"></i></h6><div class="section_content"><div class="section_prods"><span>عدد المنتجات: </span><span class="section_prods_num">'+response.data[1]+'</span></div><p>الأقسام الفرعية:</p><ul class="list-group">'+response.data[2]+'</ul><div class="section_options"><div class="div"><div class="section_delete"><a id="'+response.data[3]+'" class="btn btn-danger text-light total-del-section">حذف القسم <i class="fas fa-trash"></i></a></div></div><div class="section_edit"><div class="div"><a id="'+response.data[3]+'" class="btn btn-primary text-light edit-section">تعديل القسم <i class="fas fa-edit"></i></a></div></div></div></div></div>');

                    document.getElementById("spinner_cont").style.display="none";
                    
                })
                .catch(function(error)
                {

                });
            }
        })
        .catch(function(error)
        {

        });

        //Go To Edit page
        $(document).on("click", ".edit-section", function()
        {
            let thisurl = this.id;
            self.$router.push({path: "/edit_section/"+thisurl});
        });

        //Delete Confirmation MSG
        $(document).on("click", ".total-del-section", function()
        {
            let sec_del_id =  $(this).attr("id");

            Notiflix.Confirm.Show( 'تأكيد الحذف', 'حذف هذا القسم وجميع المنتجات التي فيه؟', 'نعم', 'لا', function()
            { 
                axios.get("/section_delete/"+sec_del_id)
                .then(function(response)
                {
                    self.$store.state.statererendercomp++;
                })
                .catch(function(error)
                {

                });
            }, 
            function()
            { 
                
            }); 
        });

    },
    methods:
    {

    }

}
</script>
<style scoped>

</style>