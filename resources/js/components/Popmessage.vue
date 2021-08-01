<template>
  <div class="msg_pop_main">

    <div class="pop_overlay_success" id="pop_overlay_success">
      <div id="container" class="pop_msg">
        <div id="success-box" class="msg_inner">
          <div class="face">
            <div class="eye"></div>
            <div class="eye right"></div>
            <div class="mouth happy"></div>
          </div>
          <div class="shadow scale"></div>
          <div class="message"><h1 class="alert">سيظهر تعليقك بعد مراجعته</h1></div>
          <button class="button-box close_pop_msg"><h1 class="greeno">موافق</h1></button>
        </div>
      </div>
    </div>

    <div class="pop_overlay_error" id="pop_overlay_error">
      <div id="container" class="pop_msg">
          <div id="error-box" class="msg_inner">
            <div class="face2">
              <div class="eye"></div>
              <div class="eye right"></div>
              <div class="mouth sad"></div>
            </div>

            <div class="shadow move"></div>
            <div class="message">
              <h1 class="alert">حدث خطأ ما</h1>
            </div>
            <button class="button-box close_pop_msg"><h1 class="red">موافق</h1></button>
          </div>
        </div>
      </div>

  </div>
</template>
<script>
export default {
    name: "Popmessage",
    mounted()
    {
      $(".close_pop_msg").on("click", function()
      {
        $(this).closest('#pop_overlay_error').fadeOut();
        $(this).closest('#pop_overlay_success').fadeOut();
      });
    },
}
</script>
<style scoped>
.msg_pop_main
{
  font-family: 'Tajawal', sans-serif;
}
.pop_overlay_success
{
  position: fixed;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  z-index: 2000000;
  top:0;
  left: 0;
  display: none;
}
.pop_overlay_error
{
  position: fixed;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  z-index: 2000000;
  top:0;
  left: 0;
  display: none;
}
.pop_msg
{
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
}
.msg_inner
{
  width: 400px;
  height: 400px;
}
.pop_msg h1
 {
    font-size: 18px;
    font-weight: 100;
    color: #fff;
    margin-bottom: 0px !important;
}
 .pop_msg .greeno{
    color: #4ec07d;
}
 .pop_msg .red{
    color: #e96075;
}
 .pop_msg .alert{
    font-weight: 700;
}
 .pop_msg p{
    margin-top: -5px;
    font-size: 0.5em;
    font-weight: 100;
    color: #5e5e5e;
}
 .pop_msg button, .pop_msg .dot{
    cursor: pointer;
}
 .pop_msg #success-box{
    position: absolute;
    background: linear-gradient(to bottom right, #b0db7d 40%, #99dbb4 100%);
    border-radius: 20px;
    perspective: 40px;
}
 .pop_msg #error-box{
    position: absolute;
    background: linear-gradient(to bottom left, #ef8d9c 40%, #ffc39e 100%);
    border-radius: 20px;
}
 .pop_msg .two{
    right: 12%;
    opacity: 0.5;
}
 .pop_msg .face{
    position: absolute;
    width: 22%;
    height: 22%;
    background: #fcfcfc;
    border-radius: 50%;
    border: 1px solid #777;
    top: 21%;
    left: 37.5%;
    z-index: 2;
    animation: bounce 1s ease-in infinite;
}
 .pop_msg .face2{
    position: absolute;
    width: 22%;
    height: 22%;
    background: #fcfcfc;
    border-radius: 50%;
    border: 1px solid #777;
    top: 21%;
    left: 37.5%;
    z-index: 2;
    animation: roll 3s ease-in-out infinite;
}
 .pop_msg .eye{
    position: absolute;
    width: 5px;
    height: 5px;
    background: #777;
    border-radius: 50%;
    top: 40%;
    left: 20%;
}
 .pop_msg .right{
    left: 68%;
}
 .pop_msg .mouth{
    position: absolute;
    top: 43%;
    left: 41%;
    width: 7px;
    height: 7px;
    border-radius: 50%;
}
 .pop_msg .happy{
    border: 2px solid;
    border-color: transparent #777 #777 transparent;
    transform: rotate(45deg);
}
 .pop_msg .sad{
    top: 49%;
    border: 2px solid;
    border-color: #777 transparent transparent #777;
    transform: rotate(45deg);
}
.pop_msg .shadow
{
    position: absolute;
    width: 21%;
    height: 3%;
    opacity: 0.5;
    background: #777;
    left: 40%;
    top: 43%;
    border-radius: 50%;
    z-index: 1;
}
.pop_msg .scale
{
    animation: scale 1s ease-in infinite;
}
 .pop_msg .move
{
    animation: move 3s ease-in-out infinite;
}
.pop_msg .message
{
    position: absolute;
    width: 100%;
    text-align: center;
    height: 40%;
    top: 47%;
}
 .pop_msg .button-box
{
  position: absolute;
  background: #fcfcfc;
  width: 50%;
  height: 15%;
  border-radius: 20px;
  top: 73%;
  left: 25%;
  outline: 0;
  border: none;
  box-shadow: 2px 2px 10px rgba(119, 119, 119, .5);
  transition: all 0.5s ease-in-out;
}
.pop_msg .button-box:hover
{
  background: #efefef;
  transform: scale(1.05);
  transition: all 0.3s ease-in-out;
}
@keyframes bounce
{
  50%
 {
    transform: translateY(-10px);
 }
}
@keyframes scale
{
  50%
 {
    transform: scale(0.9);
 }
}
@keyframes roll
{
  0%
 {
    transform: rotate(0deg);
    left: 25%;
 }
  50%
 {
    left: 60%;
    transform: rotate(168deg);
 }
  100%
 {
    transform: rotate(0deg);
    left: 25%;
 }
}
@keyframes move 
{
  0% 
 {
    left: 25%;
 }
  50%
 {
    left: 60%;
 }
  100%
 {
    left: 25%;
 }
}
.pop_msg footer
{
  position: absolute;
  bottom: 0;
  right: 0;
  text-align: center;
  font-size: 1em;
  text-transform: uppercase;
  padding: 10px;
}
.pop_msg footer p
{
  color: #ef8d9c;
}
.pop_msg footer a
{
  color: #b0db7d;
  text-decoration: none;
}
.pop_msg footer a:hover
{
  color: #ffc39e;
}
@media(max-width: 600px)
{
  .msg_inner
  {
    width: 300px;
    height: 300px;
  }
}
</style>