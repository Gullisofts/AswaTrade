<template>
    <div>
        <div class="container-fluid">
            <div class="dash_bod">
                <h5 class="main_heading">تعديل القسم</h5>

                <form class="main-gen_form sections_edit">

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="msectionname">اسم القسم الرئيسي</label>
                            <input type="text" v-bind:value="sectiondata[0]" class="form-control msectionname" placeholder="اسم القسم الرئيسي">
                        </div>
                    </div>
                    <div class="form-group mb-4">

                        <label>صورة القسم <small>(يفضل أن تكون مربعة الطول يساوي العرض أو قريبة جدا من هذه النسبة)</small></label>
                            <div class="custom-file">
                            <input type="file" class="custom-file-input sectionimg" id="customFile" enctype="multipart/form-data">
                            <label style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="custom-file-label text-center" for="customFile">أختر صورة</label>
                        </div>

                        <div class="form-group mt-4 img-thumbnail">
                            <div class="section_img_preview">
                                <img v-if="sectiondata[3] != null" v-bind:src="'/images/sections/'+sectiondata[3]" alt="صورة القسم">
                                <img v-if="sectiondata[3] == null" src="/images/temp/noimage.jpg" alt="صورة القسم">
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label>وصف القسم</label>
                        <textarea class="form-control msectiondesc">{{sectiondata[1]}}</textarea>
                    </div>

                    <hr class="section_hr mt-4 mb-4">
                    <h6 class="mt-3 mb-4 mini_heading">الأقسام الفرعية</h6>
                    <div class="form-group subsections">
                        <p class="alert alert-danger subsections_error"></p>

                        <template v-if="sectiondata[2] != '' ">
                            <div class="form-group subsection img-thumbnail mb-3" v-for="(value, key, index) in sectiondata[2]" :key="`${ key }-${ index }`">
                                
                                <div class="form-group">
                                    <label>اسم القسم الفرعي</label>
                                    <input type="text" v-bind:value="key" class="form-control subsectionname" placeholder="اسم القسم الفرعي">
                                </div>
                                <div class="form-group">
                                    <label>وصف القسم</label>
                                    <p class="text-muted font-weight-bold">إذا كنت تريد الوصف فارغ ضع كلمة (لا يوجد)</p>
                                    <input type="hidden" class="subsectionid" v-bind:value="value.split('identifier_xXxX85215_')[1]">
                                    <textarea class="form-control subsectiondesc">{{value.split('identifier_xXxX85215_')[0]}}</textarea>
                                </div>

                                <div class="form-group">
                                    <a class="btn btn-danger delete_subsection">حذف القسم</a>
                                </div>

                            </div>
                        </template>

                        <template v-if="sectiondata[2] == ''">
                            <p class="nosubsmsg text-center">لا يوجد أقسام فرعية</p>
                            <hr class="mt-3 mb-3">
                        </template>

                    </div>

                    <div class="form-group col-12 add_subsection_div mt-5">
                        <a class="btn btn-primary" id="add_new_subsection">إضافة قسم</a>
                    </div>

                    <div class="form-group col-12 save_section_div mt-3">
                        <a id="saveeditedsection" class="btn btn-primary mb-2 save_section_btn">حفظ</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {

    components: {

        axios,
    },
    data()
    {
        return {

            sectionId: this.$route.params.sectionId,
            sectiondata: [],
            sectionsidsM: [],
        }
    },
    mounted:function()
    {
        let self = this;
        document.getElementById("spinner_cont").style.display="block";

        //Get All Section Data
        axios.get("/geteditsectiondata/"+self.sectionId)
        .then(function(response)
        {
            self.sectiondata = response.data;

            for (const [key, value] of Object.entries(self.sectiondata[2]))
            {
                self.sectionsidsM.push(value.split('identifier_xXxX85215_')[1]);
            }

            document.getElementById("spinner_cont").style.display="none";
        })
        .catch(function(error)
        {

        });

        let newsubid="";
        //Add New Sub Section
        $("#add_new_subsection").click(function()
        {
            newsubid = Math.random().toString(36).substring(7);

            $(".subsections_error").fadeOut(1);

            $(".subsections").append('<div class="form-group subsection img-thumbnail mb-3"><div class="form-group"><label for="subsectionname">اسم القسم الفرعي</label><input type="text" v-bind:value="key" class="form-control subsectionname" placeholder="اسم القسم الفرعي"></div><div class="form-group"><label for="subsectiondesc">وصف القسم</label><p class="text-muted font-weight-bold">إذا كنت تريد الوصف فارغ ضع كلمة (لا يوجد)</p><input type="hidden" class="subsectionid" value="'+newsubid+'"><textarea class="form-control subsectiondesc"></textarea></div><div class="form-group"><a class="btn btn-danger delete_subsection">حذف القسم</a></div></div>');

            self.sectionsidsM.push(newsubid);

        });

        //Remove Subsection
        $(document).on("click", ".delete_subsection", function()
        {
            if($(".subsections").children().length < 3)
            {
                $(".subsections_error").fadeIn(1);
                $(".subsections_error").html("لابد أن يكون هنالك قسم فرعي واحد على الأقل");
            }
            else
            {
                $(this).closest(".subsection").remove();
                $(".subsections_error").fadeOut(1);
                
               let idindex = self.sectionsidsM.indexOf($(this).closest(".subsection").children().find(".subsectionid").val());
               self.sectionsidsM.splice(idindex, 1);
               
            }
            
        });

        //Save Edited Section
        $("#saveeditedsection").click(function()
        {
            document.getElementById("spinner_cont").style.display="block";

            //Save Edited Section
            let msectionname = $(".msectionname").val();
            let msectiondesc = $(".msectiondesc").text();

            let subsectionnames = "";
            $('.subsections .subsectionname').map(function()
            {
                subsectionnames += $(this).val() + "_xXnextarrayelxX_";

            }).get();

            let subsectiondescs = "";
            $('.subsections .subsectiondesc').map(function()
            {
                subsectiondescs += $(this).val() + "_xXnextarrayelxX_";

            }).get();

           let subsectionids = "";
           for(let i=0; i<self.sectionsidsM.length; i++)
           {
                subsectionids += self.sectionsidsM[i]+"_xXnextarrayelxX_";
           }
            

            
            let sectionId= this.sectionId;

            let data = new FormData();
            let section_image =  $(".sectionimg").get(0).files[0];
            data.append("sectionimg", section_image);
            data.append("msectionname", msectionname);
            data.append("msectiondesc", msectiondesc);
            data.append("subsectionnames", subsectionnames);
            data.append("subsectiondescs", subsectiondescs);
            data.append("subsectionids", subsectionids);
            data.append("sectionId", self.sectionId);


            const config = {headers:{'Content-Type': 'multipart/form-data'}};

            axios.post("/saveeditedsection", data, config)
            .then(function(response)
            {
                if(response.data == "success")
                {
                    setTimeout(function()
                    {
                        document.getElementById("spinner_cont").style.display="none";
                        self.$store.state.statererendercomp++;

                    }, 1000);
                }
                else if(response.data == "subsectionsmissingsomething")
                {
                    document.getElementById("spinner_cont").style.display="none";

                    Notiflix.Notify.Failure('حدث خطأ ما!، يرجى مراجعة البيانات المدخلة');
                }
                else if(response.data == "nosectionname")
                {
                    document.getElementById("spinner_cont").style.display="none";

                    Notiflix.Notify.Failure('اسم القسم ضروري');
                }
                else if(response.data == "usedsectionname")
                {
                    document.getElementById("spinner_cont").style.display="none";
                    
                    Notiflix.Notify.Failure('اسم القسم مستخدم من قبل');
                }
            })
            .catch(function(error)
            {

            });
            
        });

    }

}
</script>
<style scoped>

</style>