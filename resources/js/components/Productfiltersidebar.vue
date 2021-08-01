<template>
    <div class="prodfilter shadow-sm p-3 bg-white rounded">
        <ul class="main_list">
            <li>
                <a class="model">
                    <span class="icon"><i class="fas fa-bars"></i></span>

                    <div class="img-thumbnail">

                        <h6 class="img-thumbnail">الموديل</h6>
                        <span>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="">
                                <label class="form-check-label">الموديل 1</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="">
                                <label class="form-check-label">الموديل 2</label>
                            </div>
                        </span>

                        <h6 class="mt-2 img-thumbnail">السعر</h6>
                        <span>
                            <div class="form-group">
                                <label class="mr-0" for="pricefrom">من</label>
                                <input type="text" class="form-control" id="pricefrom" placeholder="السعر من">
                            </div>
                            <div class="form-group">
                                <label class="mr-0" for="priceto">إلى</label>
                                <input type="text" class="form-control" id="priceto" placeholder="السعر إلى">
                            </div>
                        </span>

                        <h6 class="mt-2 img-thumbnail">التقييم بالنجوم</h6>
                        <span>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="onestar">
                                <label class="form-check-label" for="onestar">1</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="twostar">
                                <label class="form-check-label" for="twostar">2</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="threestar">
                                <label class="form-check-label" for="threestar">3</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="fourstar">
                                <label class="form-check-label" for="fourstar">4</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="fivestar">
                                <label class="form-check-label" for="fivestar">5</label>
                            </div>
                        </span>
                    </div>
    
                </a>
            </li>

        </ul>
    </div>
</template>

<script>
import axios from 'axios'
export default {

    name: "Productfiltersidebar",
    components: {
        axios,
    }
}
</script>

<style scoped>
/* SideBar */
.prodfilter
{
    position: absolute;
    width: 60px;
    height: 100%;
    background: #282846;
    transition: 0.5s ease-in-out;
    overflow: hidden;
    z-index: 10;
    margin-top: 20px;
}
.prodfilter h6
{
    background: #dcdde1;
    color: #fff;
    width: 100%;
    padding-top:7px;
    padding-bottom:7px;
    text-align: center;
    color: #000;
}
.prodfilter *
{
    direction: rtl;
}
.prodfilter .form-control
{
   font-size: 16px !important;
}
.prodfilter:hover
{
    width: 300px;
}
.prodfilter .main_list
{
    position: absolute;
    top: 0;
    right: 0;
    padding-right: 0;
    width: 100%;
}

.prodfilter .main_list li
{
    position: relative;
    width: 100%;
    list-style-type: none;
}
.prodfilter .main_list li:not(:first-child)
{
    margin-top: 7px;
}

.prodfilter .main_list li a
{
    position: relative;
    width: 100%;
    display: flex;
    text-decoration: none;
    color: #000;
}
.prodfilter .main_list li a .icon
{
    min-width: 60px;
    max-width: 60px;
    height: 100%;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    text-align: center;
    color:#fed049;
    padding-top: 10px;
    cursor: pointer;
}
.prodfilter .main_list li a .icon .fas
{
    font-size: 24px;
}
.prodfilter .main_list li a > div
{
    position: relative;
    padding: 0 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    color: #000;
    width: 100%;
    padding-top:10px;
    padding-bottom:10px;
}
.prodfilter .main_list li a > div span
{
    direction: rtl;
    text-align: right;
}
.prodfilter .main_list li .model > div span label
{
    margin-right: 20px;
}
.prodfilter .main_list li .rating_sidebar > div span label
{
    margin-right: 20px;
}

</style>