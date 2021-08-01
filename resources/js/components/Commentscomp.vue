<template>
    <div class="comments_section">
        <div class="add_opinion">
            <template v-if="loginStatus == 'loggedin'">
                <div class="p-2">
                    <div class="add_star_rating img-thumbnail">
                        <h6 class="prod_h6 text-center">قيم المنتج</h6>
                        <div class="rating-css">
                            <div class="star-icon">
                                <input type="radio" class="rating_u" name="rating1" id="rating1" checked>
                                <label for="rating1" class="fa fa-star"></label>
                                <input type="radio" class="rating_u" name="rating1" id="rating2">
                                <label for="rating2" class="fa fa-star"></label>
                                <input type="radio" class="rating_u" name="rating1" id="rating3">
                                <label for="rating3" class="fa fa-star"></label>
                                <input type="radio" class="rating_u" name="rating1" id="rating4">
                                <label for="rating4" class="fa fa-star"></label>
                                <input type="radio" class="rating_u" name="rating1" id="rating5">
                                <label for="rating5" class="fa fa-star"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <p class="text-center no_login_comments">يرجى تسجيل الدخول لتتمكن من إضافة مراجعات | <span class="loginbtn" @click="gotologin">تسجيل الدخول</span></p>
            </template>

            <div class="add_comment">
                <div class="container-fluid mt-2 mb-3">
                    <div class="d-flex justify-content-center row">

                        <div class="d-flex flex-column col-12 comments_css">

                            <div class="coment-bottom bg-white p-2 px-4">

                                <template v-if="loginStatus == 'loggedin'">
                                    <div class="row add-comment-section mt-4 mb-4">
                                        <button @click="addcomment" class="btn btn-primary col-lg-1 col-md-2 col-sm-2 col-3 mt-sm-0 mt-2 order-last" type="button">أضف</button>
                                        <input id="user_comment" v-model="commentcontent" type="text" class="form-control mr-3 col-lg-11 col-md-10 col-sm-10 order-first" placeholder="ما رأيك في المنتج...">
                                    </div>
                                </template>

                                <div class="commented-section mt-5">

                                    <h6 class="mb-3 font-weight-bold">المراجعات</h6>

                                    <template v-if="comments == ''">
                                        <p>لا يوجد مراحعات لهذا المنتج</p>
                                    </template>
                                    <template v-if="comments != ''">
                                        <div v-for="commento in comments" class="comment_user img-thumbnail p-3 mt-3 mt-0:last-of-type">
                                            <div class="d-flex flex-column justify-content-center commented-user">
                                                <h5 class="mr-2 commenter_name">{{commento.user_name}}</h5>
                                                <span class="mb-1 ml-2">{{commento.created_at}}</span>
                                            </div>

                                            <div class="comment-text-sm">
                                                <span>{{commento.content}}</span>
                                            </div>
                                        </div>
                                    </template>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <Popmessage></Popmessage>
    </div>
    
</template>

<script>

import axios from "axios";
import Popmessage from "./Popmessage";

export default {

    name: "Commentscomp",
    data() {

        return {

            loginStatus: "",
            comments:"",
            commentcontent:"", 
        }
    },
    components: 
    {
        axios,
        Popmessage,
    },
    created:function()
    {
        //Set Self
        let self = this;

        //Get Login Status
        axios.get("/chklogin")
        .then(function(response)
        {
            if(response.data == "loggedin")
            {   
                self.loginStatus = "loggedin";
            }
        })
        .catch(function(error)
        {

        });

        //Get Product Comments
        let productid = this.$store.state.currentProd;

        axios.get("/getproductcomments/"+productid)
        .then(function(response)
        {
           self.comments = response.data;
        })
        .catch(function(error)
        {

        });

        //Add Rating Stars
        $(function()
        {
            $(document).on('click', '.rate', function()
            {
                if (!$(this).find('.star').hasClass('rate-active'))
                {
                    $(this).siblings().find('.star').addClass('far').removeClass('fas rate-active');
                    $(this).find('.star').addClass('rate-active fas').removeClass('far star-over');
                    $(this).prevAll().find('.star').addClass('fas').removeClass('far star-over');
                } 
            });
        });

        //Store Star Rating
        $(document).on("click", ".rating_u", function(e)
        {
            document.getElementById("spinner_cont").style.display="block";
            
            e.stopImmediatePropagation();
            e.stopPropagation();

            let rating_val = "";
            let productid = self.$store.state.currentProd;
            $(".star-icon input[type=radio]:checked").each( function()
            {
               rating_val = $(this).attr("ID");
            });

            axios.post("/storerating", {rating_val, productid})
            .then(function(response)
            {
                if(response.data == "wrongdata")
                {
                    Notiflix.Notify.Failure('حدث خطأ ما!');
                }
                if(response.data == "success")
                {
                    Notiflix.Notify.Success('تم إضافة تقييمك بنجاح');
                }

                document.getElementById("spinner_cont").style.display="none";
                
            })
            .catch(function(error)
            {

            });
        });

        //Confirm Comment Addition after Revision
        $(document).on("click", "#NXReportButton", function()
        {
            self.$store.state.statererendercomp++
        })


    },
    methods: {
        gotologin:function()
        {
            this.$store.state.fromProduct = this.$store.state.currentProd;
            this.$router.push("/login");
        },
        addcomment:function()
        {
            //Get Comment
            let comment = this.commentcontent;
            let prod_id = this.$store.state.currentProd;
            let self = this;

            //Check If Comment Empty && Check Comment Length
            if(comment.trim().length < 3)
            {
                Notiflix.Notify.Failure('التعليق لا يجب أن يكون فارغ ولابد أن لا يقل عن 3 أحرف');
            }
            else
            {
                //Store Comment
                axios.post("/storecomment", {comment, prod_id}).
                then(function(response)
                {
                    
                    if(response.data == "no prod found")
                    {
                        Notiflix.Notify.Failure('حدث خطأ ما!');
                    }

                    if(response.data == "not loggedin")
                    {
                        Notiflix.Notify.Failure('حدث خطأ ما!');
                    }

                    if(response.data == "wait")
                    {
                        Notiflix.Report.Success(
                        'اضافة تعليق',
                        'سيتم اضافة تعليقة بعد مراجعته والموافقة عليه',
                        'موافق');
                    }
                    if(response.data == "add")
                    {
                        Notiflix.Report.Success(
                        'اضافة تعليق',
                        'تم إضافة تعليقك بنجاح',
                        'موافق');
                    }
                })
                .catch(function(error)
                {

                    if(error.response.data.errors)
                    {
                        Notiflix.Notify.Failure(error.response.data.errors.comment[0]);
                    }

                });
            }
        },
    
    }
    
}
</script>
<style scoped>
/* Stars */
.rating-css
{
  padding: 5px;
}
.rating-css div {
  color: #ffc000;
  font-size: 18px;
  font-family: sans-serif;
  font-weight: 800;
  text-align: center;
  text-transform: uppercase;
  padding: 5px 0;
}
.rating-css input {
  display: none;
}
.rating-css input + label {
  font-size: 30px;
  cursor: pointer;
}
.rating-css input:checked + label ~ label {
  color: #838383;
}
.rating-css label:active {
  transform: scale(0.8);
  transition: 0.3s ease;
}
/* Comments */
.comments_css
{
    padding: 0 !important;
}
.add_comment .container-fluid
{
    padding: 0 !important;
}
.add_comment .bdge 
{
    height: 21px;
    background-color: orange;
    color: #fff;
    font-size: 11px;
    padding: 8px;
    border-radius: 4px;
    line-height: 3px
}
.add_comment .mr-3, .mx-3
{
    margin-right: 0 !important;
}
.add_comment .mr-2, .mx-2 
{
    margin-right: 0 !important;
}

.add_comment .comments
{
    text-decoration: underline;
    text-underline-position: under;
    cursor: pointer
}

.add_comment .dot
{
    height: 7px;
    width: 7px;
    margin-top: 3px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block
}

.add_comment .hit-voting:hover
{
    color: blue
}

.add_comment .hit-voting
{
    cursor: pointer
}
.add_comment .add-comment-section
{
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
}
.add_comment .add-comment-section input
{
    text-align: right;
    direction: rtl;
}
.add_comment .commented-section
{
    text-align: right;
    direction: rtl;
}
.add_comment .commented-section *
{
    text-align: right;
    direction: rtl;
}
.commenter_name
{
    background: #ecf0f1;
    padding-top:5px;
    padding-bottom:5px;
}
.add_comment button
{
    border-radius: 0;
}
.loginbtn
{
    color: #3498db;
    font-weight: bold;
    cursor: pointer;
}
.no_login_comments
{
    margin-bottom: 0;
}
.add-comment-section
{
    direction: rtl;
}
</style>
