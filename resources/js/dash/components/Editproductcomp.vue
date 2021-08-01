<template>
    <div class="container-fluid">
        <div class="dash_bod">
            <h5 class="main_heading">تعديل منتج</h5>

            <form class="main-gen_form" id="add-new-product">
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
                        
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="brsections_input">القسم الفرعي</label>
                        <select id="brsections_input" class="form-control">

                        </select>
                    </div>


                    <div class="form-group col-md-6">
                        <label for="produnit">الوحدة</label>
                        <select id="produnit" class="form-control">

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

                        <div class="specs_list" id="specs_list">

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

                    <div class="general_img_notice mt-4 col-12">
                        <p class="alert alert-primary m-0">ملحوظة هامة: لا تختر أي صور إذا كنت لا تريد أي تغيير في الصور</p>
                    </div>

                    <div class="form-group mt-4 col-12 img-thumbnail">
                        <h5 class="innerh55">الصورة الرئيسية الحالية</h5>
                        <div class="prod_mimg_preview img-thumbnail">

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

                    <div class="form-group mt-4 col-12 img-thumbnail">
                        <h5 class="innerh55">صور المنتج الحالية</h5>
                        <div class="prod_imgs_preview row" id="prod_imgs_preview">

                        </div>
                    </div>

                    <div class="form-group col-md-12 mt-4 text-center">
                        <a id="editprodindb" style="cursor:pointer;" class="btn btn-primary text-light">حفظ التعديلات</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import Popmessage from "./Popmessage.vue";
import axios from "axios";
export default {

    components: {
        
        Popmessage,
        axios,
    },
    data()
    {
        return {

            prodid: this.$route.params.prodid,
            proddata: [],
            mainsections: [],
            brsections: [],  
        }
    },
    mounted:function()
    {
        let self = this;
        document.getElementById("spinner_cont").style.display="block";

        ////////// Get Prod Data /////////////
        let prod_id = self.prodid;

        axios.get("/getproddata/" + prod_id)
        .then(function(response)
        {
            $("#prodname").val(response.data[3]);
            $("#prodqty").val(response.data[8]);
            $("#prodprice").val(response.data[4]);
            $("#prodcode").val(response.data[5]);
            $("#proddesc").val(response.data[11]);
            $("#prodmanu").val(response.data[6]);
            $("#proddiscount").val(response.data[10]); 

            let msectionidd = response.data[1];
            let subsectionidd = response.data[2];
            
            //Get Main Secions
            axios.get("/getmainsections")
            .then(function(response)
            {
                self.mainsections = response.data;
                for (const [key, value] of Object.entries(response.data))
                {
                    if(value == msectionidd)
                    {
                        document.getElementById("mainsections_input").innerHTML += "<option value="+value+" selected>"+key+"</option>";
                    }
                    else
                    {
                        document.getElementById("mainsections_input").innerHTML += "<option value="+value+">"+key+"</option>";
                    }

                }
            })
            .catch(function(error)
            {

            });

            //Get SubSectios
            axios.get("/getsubsections/"+msectionidd)
            .then(function(response)
            {
                self.brsections = response.data;

                for (const [key, value] of Object.entries(response.data))
                {
                    if(value == subsectionidd)
                    {
                        document.getElementById("brsections_input").innerHTML += "<option value="+value+" selected>"+key+"</option>";
                    }
                    else
                    {
                        document.getElementById("brsections_input").innerHTML += "<option value="+value+">"+key+"</option>";
                    }

                }
                document.getElementById("spinner_cont").style.display="none";
            })
            .catch(function(error)
            {

            });

            //Inserting Units
            let unitsarr = ["كرتونة", "قطعة", "علبة", "كيلو", "رطل", "كيس"];

            for (let i=0; i<unitsarr.length; i++)
            {
                if(unitsarr[i] == response.data[7])
                {
                    document.getElementById("produnit").innerHTML += "<option selected>"+unitsarr[i]+"</option>";
                }
                else
                {
                    document.getElementById("produnit").innerHTML += "<option>"+unitsarr[i]+"</option>";
                }

            }

            //Insert Specs

            for(const [key, value] of Object.entries(response.data[12]))
            {

                document.getElementById("specs_list").innerHTML += '<div class="form-group w-100 spec_unit row"><input type="text" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-sm-3 col-12 prodspectitle" value="'+value[0]+'" placeholder="الخاصية"><input type="text" value="'+value[1]+'" style="border-radius: 0rem 0rem 0rem 0rem;" class="form-control col-sm-8 col-12  prodspecval" placeholder="الوصف"><a style="border-radius: 0.25rem 0rem 0rem 0.25rem;cursor:pointer;" class="btn btn-danger col-sm-1 col-12 mt-sm-0 mt-2"><i class="fas fa-minus remove_spec text-light"></i></a></div>';
            }


            //Insert Main Product Image
            $(".prod_mimg_preview").html('<img src="'+response.data[13][0]+'" alt="صورة المنتج الرئيسية">');

            //Insert Product Images
            for(let i=1; i<response.data[13].length; i++)
            {
               document.getElementById("prod_imgs_preview").innerHTML += '<div class="col-md-6 col-sm-12 img-thumbnail"><img src="'+response.data[13][i]+'"></div>';
            }

            

        })
        .catch(function(error)
        {

        });



        //Get Branched Sections On Main Section Change
        $("#mainsections_input").on("change", function()
        {
            axios.get("/getbrsections/"+$(this).val())
            .then(function(response)
            {
                document.getElementById("brsections_input").innerHTML = "";
                for (const [key, value] of Object.entries(response.data))
                {
                    document.getElementById("brsections_input").innerHTML += "<option value="+value+">"+key+"</option>";
                }
            })
            .catch(function(error)
            {

            });
        });


        //Save Edited Product
        $("#editprodindb").click(function()
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

            data.append('prod_images', "");
            
            for(let i = 0; i <  $("#prodpics").get(0).files.length; i++)
            {
                let file = $("#prodpics").get(0).files[i];
                data.append('prod_images[' + i + ']', file);
            }
            
            data.append("prod_id", prod_id);
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
            axios.post("/saveeditedproduct", data, config)
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
            $(".specs_list").append('<div class="form-group d-flex flex-row justify-content-center align-content-center spec_unit"><input type="text" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-3 prodspectitle" placeholder="الخاصية"><input type="text" style="border-radius: 0rem 0rem 0rem 0rem;" class="form-control col-8 prodspecval" placeholder="الوصف"><a type="submit" style="border-radius: 0.25rem 0rem 0rem 0.25rem; cursor:pointer;" class="btn btn-danger col-1"><i class="fas fa-minus remove_spec text-light"></i></a></div>');
            
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