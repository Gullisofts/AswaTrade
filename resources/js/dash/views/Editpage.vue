<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <div class="container-fluid">
            <div class="dash_bod">
                <h5 class="main_heading">إنشاء صفحة جديدة</h5>
                <div class="new_page">
                    <div class="form-group mb-3">
                        <label for="">عنوان الصفحة</label>
                        <input v-if="pagedetails != ''" type="text" class="form-control" id="subject" :value="pagedetails.title">
                        <input v-if="pagedetails == ''" type="text" class="form-control" id="subject" value="">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">المحتوى</label>
                        <textarea id="summernote" name="editordata" col="5"></textarea>
                    </div>

                    <button id="saveeditedpage" class="btn btn-primary">حفظ</button>
                </div>

            </div>
        </div> 
        <Dfooter></Dfooter>
    </div> 
</template>
<script>

import Dashsidebar from "./../components/Dashsidebar.vue";
import Dfooter from "./../components/Dfooter.vue";
import axios from "axios";

export default {

    components: {
        Dashsidebar,
        axios,
        Dfooter,
    },
    data() 
    {
        return {
            id: this.$route.params.id,
            pagedetails: "",
        }
    },
    mounted:function()
    {
        document.getElementById("spinner_cont").style.display="block";
        let self = this;

        //Prepare Text Editor
        $(document).ready(function()
        {
            $('#summernote').summernote(
            {
                height: 300,   //set editable area's height
                codemirror: { // codemirror options
                theme: 'monokai'
                },
                toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['style', ['style']],
                ['fontname', ['fontname']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview']],
            ]
            });
        });

        //Get Page Details
        let pageid = self.id;
        axios.get("/getpagedetails/"+pageid)
        .then(function(response)
        {
            self.pagedetails = response.data;

            $(document).ready(function()
            {
                $('#summernote').summernote('code', response.data.content);
            });

            document.getElementById("spinner_cont").style.display="none";

        })
        .catch(function(error)
        {

        })

        //Save Page
        $(document).on("click", "#saveeditedpage", function(e)
        {
            document.getElementById("spinner_cont").style.display="block";

            e.stopImmediatePropagation();
            e.stopPropagation();

            let subject = $("#subject").val();
            let content = $("#summernote").val();
            
            axios.post("/saveeditedpage", {pageid, subject, content})
            .then(function(response)
            {
                document.getElementById("spinner_cont").style.display="none";

                if(response.data == "nocontent")
                {
                    Notiflix.Notify.Failure('محتوى الصفحة مطلوب');
                }
                else if(response.data == "nosubject")
                {
                    Notiflix.Notify.Failure('عنوان الصفحة مطلوب');
                }
                else if(response.data == "alreadyexist")
                {
                    Notiflix.Notify.Failure('هذه الصفحة موجودة بالفعل');
                }
                else if(response.data == "success")
                {
                    Notiflix.Notify.Success('تم حفظ الصفحة بنجاح');
                    self.$router.push({path: "/pages"});
                }
                
            })
            .catch(function(error)
            {

            })
            
        });
    },

}
</script>
