<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <div class="container-fluid">
            <div class="dash_bod">
                <h5 class="main_heading">إنشاء صفحة جديدة</h5>

                <div class="new_page">
                    <div class="form-group mb-3">
                        <label for="">عنوان الصفحة</label>
                        <input type="text" class="form-control" id="subject">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">المحتوى</label>
                        <textarea id="summernote" name="editordata" col="5"></textarea>
                    </div>

                    <button id="savenewpage" class="btn btn-primary">حفظ</button>
                </div>

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

        }
    },
    mounted:function()
    {
        let self = this;

        //Prepare Text Editor
        $(document).ready(function()
        {
            document.getElementById("spinner_cont").style.display="block";
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

            document.getElementById("spinner_cont").style.display="none";
        });

        //Save Page
        $(document).on("click", "#savenewpage", function()
        {
            document.getElementById("spinner_cont").style.display="block";

            let subject = $("#subject").val();
            let content = $("#summernote").val();
            
            axios.post("/savepage", {subject, content})
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
