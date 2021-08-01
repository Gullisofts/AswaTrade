<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <div class="container-fluid">
            <div class="dash_bod">
                <h5 class="main_heading">إدارة الصفحات</h5>

                <div class="create_new_page">
                    <router-link tag="a" class="btn btn-primary" to="/createpage">إنشاء جديد</router-link>
                </div>
                <p class="mt-5 mb-0 mb-2">يمكنك ترتيب الصفحات من خلال السحب والإلقاء</p>
                <table class="table pages_table mt-4">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">اسم الصفحة</th>
                            <th class="text-center" scope="col">خيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="pages != ''">
                            <tr class="sorttr" v-for="page in pages" :id="page.id">
                                <th class="text-center"><i class="fas fa-sort ml-2"></i> {{page.title}}</th>
                                <td class="text-center">
                                    <i :id="page.id" class="fas fa-trash delete_pg text-danger ml-2"></i>
                                    <i :id="page.id" class="fas fa-edit edit_pg text-primary mr-2"></i>
                                </td>
                            </tr>
                        </template>
                        <template v-if="pages == ''">
                            <tr>
                                <th class="text-center" colspan = '2'>لا يوجد صفحات</th>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <button v-if="pages != ''" id="savearrangement" class="btn btn-primary mt-2">حفظ الترتيب</button>
                <button v-if="pages == ''" class="btn btn-primary mt-2" disabled>حفظ الترتيب</button>
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

            pages: "",
        }
    },
    mounted:function()
    {
        let self = this;
        let arrange = [];
        document.getElementById("spinner_cont").style.display="block";

        //Get Pages
        axios.get("/getpages")
        .then(function(response)
        {
            if(response.data != "" && response.data != null)
            {
                self.pages = response.data;
            }

            $(document).ready(function ()
            {
                $("table tbody").sortable();
            });

            document.getElementById("spinner_cont").style.display="none";

        })
        .catch(function(error)
        {

        });

        //Go To Edit Page
        $(document).on("click", ".edit_pg", function()
        {
            self.$router.push({path: "/edit_page/" + $(this).attr("ID")});
        });

        //Delete Page
        $(document).on("click", ".delete_pg", function(e)
        {
            e.stopImmediatePropagation();
            e.stopPropagation();

            let pageid = $(this).attr("ID");
            Notiflix.Confirm.Show( 'تأكيد الحذف', 'هل تريد حذف هذه الصفحة؟', 'نعم', 'لا', function()
            { 
                document.getElementById("spinner_cont").style.display="block";
                axios.get("/page_delete/"+pageid)
                .then(function(response)
                {
                    self.$store.state.statererendercomp++;
                    document.getElementById("spinner_cont").style.display="none";
                })
                .catch(function(error)
                {

                }); 
            }, 
            function()
            { 
                
            }); 
        });
        
        //Save Arrangement
        $(document).on("click", "#savearrangement", function()
        {
            arrange = [];

           $(document).ready(function()
           {
               Array.from($("table tbody").children()).forEach(function(el)
               {
                   arrange.push(el.id);
               });

                axios.post("/savearrangement", {arrange})
                .then(function(response)
                {
                    Notiflix.Notify.Success('تم حفظ الترتيب بنجاح');
                })
                .catch(function(error)
                {

                }); 
           })
            
        });

    },

}
</script>
