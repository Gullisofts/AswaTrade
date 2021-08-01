<template>
    <div class="container-fluid">
        <div ref="dash_bod" class="dash_bod">
            <h5 class="main_heading">أضف منتج جديد</h5>

            <form class="main-gen_form img-thumbnail" id="add-new-product">
                <div class="form-row">

                    <div class="form-group col-md-6">
                        <label for="prodname">اسم الصنف</label>
                        <input type="text" class="form-control" id="prodname" placeholder="اسم المنتج">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="prodmanu">المصنع</label>
                        <input type="text" class="form-control" id="prodmanu" placeholder="الشركة المصنعة">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="mainsections_input">القسم الرئيسي</label>
                        <select id="mainsections_input" class="form-control">
                            <option selected>أختر...</option>
                            <template v-for="(value, name) in mainsections">
                                <option v-bind:value="value">{{name}}</option>
                            </template>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="brsections_input">القسم الفرعي</label>
                        <select id="brsections_input" class="form-control">
                            <option selected>أختر...</option>
                            <template v-for="(value, name) in brsections">
                                <option v-bind:value="value">{{name}}</option>
                            </template>
                        </select>
                    </div>


                    <div class="form-group col-md-6">
                        <label for="produnit">الوحدة</label>
                        <select id="produnit" class="form-control">
                            <option selected>أختر...</option>
                            <option>كرتونة</option>
                            <option>قطعة</option>
                            <option>علبة</option>
                            <option>كيلو</option>
                            <option>رطل</option>
                            <option>كيس</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="prodqty">الكمية</label>
                        <input type="number" class="form-control" id="prodqty" placeholder="الكمية">
                    </div>
                    

                    <div class="form-group col-md-6">
                        <label for="prodprice">السعر</label>
                        <input type="text" class="form-control" id="prodprice" placeholder="سعر المنتج">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="proddiscount">الخصم (بالنسبة المئوية)</label>
                        <input type="number" class="form-control" value="0" id="proddiscount" placeholder="الخصم">
                    </div>

                    <div class="form-group col-md-12">

                        <div class="form-group mb-0">
                            <label for="prodcode">الكود</label>
                        </div>

                        <div class="form-group d-flex flex-row justify-content-center align-content-center">
                            <input type="number" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-md-10 col-sm-9 col-8" id="prodcode" placeholder="كود المنتج">
                            <a @click="generatecode" style="border-radius: 0.25rem 0rem 0rem 0.25rem;color:#fff; cursor:pointer;" class="btn btn-primary col-md-2 col-sm-3 col-4">توليد</a>
                        </div>

                    </div>

                    <div class="form-group col-md-12">
                        <label for="proddesc">وصف المنتج</label>
                        <textarea class="form-control" id="proddesc" placeholder="اسم المنتج"></textarea>
                    </div>

                    <div class="form-group col-md-12 mt-4">
                        <label for="prodspecs">مواصفات المنتج</label>

                        <div class="specs_list">
                            <div class="form-group w-100 spec_unit row">
                                <input type="text" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-sm-3 col-12 prodspectitle" placeholder="الخاصية">
                                <input type="text" style="border-radius: 0rem 0rem 0rem 0rem;" class="form-control col-sm-8 col-12  prodspecval" placeholder="الوصف">
                                <a style="border-radius: 0.25rem 0rem 0rem 0.25rem;cursor:pointer;" class="btn btn-danger col-sm-1 col-12 mt-sm-0 mt-2">
                                    <i class="fas fa-minus remove_spec text-light"></i>
                                </a>
                            </div>
                        </div>

                        <a @click="add_new_spec" style="cursor:pointer;" class="btn btn-primary col-md-1 col-sm-2 col-4">
                            <i class="fas fa-plus text-light"></i>
                        </a>
                        
                    </div>

                    <div class="form-group col-md-12 mt-4">
                        <label for="prodimgs">صورة المنتج الرئيسية</label>
                        <div class="pics_list">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="prodmpic">
                                <label style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="custom-file-label text-center" for="customFile">أختر الصورة</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12 mt-4">
                        <label for="prodimgs">صور المنتج <small>(يمكنك اختيار أكثر من صورة دفعة واحدة)</small></label>
                        <div class="pics_list">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="prodpics" multiple>
                                <label style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="custom-file-label text-center" for="customFile">أختر الصور</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12 mt-4 text-center">
                        <a id="addprodtodb" style="cursor:pointer;" class="btn btn-primary text-light">أضف المنتج</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import Popmessage from "./../components/Popmessage.vue";
import axios from "axios";
export default {

    components: {
        
        Popmessage,
        axios,
    },
    data()
    {
        return {

            mainsections: [],
            brsections: [],
        }
    },
    mounted:function()
    {
        let self = this;
        document.getElementById("spinner_cont").style.display="block";

        //Get Main Secions
        axios.get("/getmainsections")
        .then(function(response)
        {
            self.mainsections = response.data;
            document.getElementById("spinner_cont").style.display="none";
        })
        .catch(function(error)
        {

        });

        //Get Branched Sections
        $("#mainsections_input").on("change", function()
        {
            document.getElementById("spinner_cont").style.display="block";
            axios.get("/getbrsections/"+$(this).val())
            .then(function(response)
            {
                self.brsections = response.data;
                document.getElementById("spinner_cont").style.display="none";
            })
            .catch(function(error)
            {

            });
        });

        //Save New Product
        $("#addprodtodb").click(function()
        {
            document.getElementById("spinner_cont").style.display="block";

            //Prod Info
            let prodname = $("#prodname").val();
            let mainsections_input = $("#mainsections_input").val();
            let brsections_input = $("#brsections_input").val();
            let produnit = $("#produnit").val();
            let prodqty = $("#prodqty").val();
            let prodprice = $("#prodprice").val();
            let prodcode = $("#prodcode").val();
            let proddesc = $("#proddesc").val();
            let prodmanu = $("#prodmanu").val();
            let proddiscount = $("#proddiscount").val();
            
            let prodspecs = "";
            $('.specs_list .prodspectitle').map(function()
            {
                prodspecs += $(this).val() + "_xXnextarrayelxX_" + $(this).siblings().eq(0).val()+ "_xXnextarrayelxX_";

            }).get();

            let data = new FormData();
            let prod_image =  $("#prodmpic").get(0).files[0];

            for(let i = 0; i <  $("#prodpics").get(0).files.length; i++)
            {
                let file = $("#prodpics").get(0).files[i];
                data.append('prod_images[' + i + ']', file);
            }

            data.append("prodname", prodname);
            data.append("mainsections_input", mainsections_input);
            data.append("brsections_input", brsections_input);
            data.append("produnit", produnit);
            data.append("prodqty", prodqty);
            data.append("prodprice", prodprice);
            data.append("prodcode", prodcode);
            data.append("proddesc", proddesc);
            data.append("prodspecs", prodspecs);
            data.append("prod_image", prod_image);
            data.append("prodmanu", prodmanu);
            data.append("proddiscount", proddiscount);
            

            const config = {headers:{'Content-Type': 'multipart/form-data'}};
            axios.post("/savenewproduct", data, config)
            .then(function(response)
            {
                document.getElementById("spinner_cont").style.display="none";

                if(response.data == "nomainsection")
                {
                    Notiflix.Notify.Failure('لم تختر القسم الرئيسي');
                }
                else if(response.data == "nobrsection")
                {
                    Notiflix.Notify.Failure('لم تختر القسم الفرعي');
                }
                else if(response.data == "noprodunit")
                {
                    Notiflix.Notify.Failure('لم تختر وحدة المنتج');
                }
                else if(response.data == "specspairs")
                {
                    Notiflix.Notify.Failure('يجب وضع مواصفات المنتج بصورة صحيحة');
                }
                else if(response.data == "mainimgnotselected")
                {
                    Notiflix.Notify.Failure('لم تختر الصورة الرئيسية للمنتج');
                }
                else if(response.data == "success")
                {
                    self.$store.state.statererendercomp++;
                }

                document.getElementById("spinner_cont").style.display="none";

            })
            .catch(function(error)
            {
                if(error.response.data.errors)
                {
                    let values = Object.values(error.response.data.errors);
                    
                    document.getElementById("spinner_cont").style.display="none";
                    
                    Notiflix.Notify.Failure(values[0]);
                }

            });
            
        });



        // Organizing Functions
        $(document).on("change", ".custom-file-input", function()
        {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        
        $(document).on("click", ".remove_spec", function()
        {

            $(this).closest(".spec_unit").remove();
            
        });

        $(document).on("click", ".remove_pic", function()
        {
            if($(".pics_list").children().length > 1)
            {
                $(this).closest(".pic_unit").remove();
            }
        });
        

    },
    methods:
    {
        add_new_spec:function()
        {
            $(".specs_list").append('<div class="form-group w-100 spec_unit row"><input type="text" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-sm-3 col-12 prodspectitle" placeholder="الخاصية"><input type="text" style="border-radius: 0rem 0rem 0rem 0rem;" class="form-control col-sm-8 col-12  prodspecval" placeholder="الوصف"><a style="border-radius: 0.25rem 0rem 0rem 0.25rem;cursor:pointer;" class="btn btn-danger col-sm-1 col-12 mt-sm-0 mt-2"><i class="fas fa-minus remove_spec text-light"></i></a></div>');     
        },
        generatecode:function()
        {

            $("#prodcode").val(Math.floor(Math.random() * 1000000000000000));
        }
    }

}
</script>
<style scoped>
.add-new-product a i
{
    color: #fff !important;
}
</style>