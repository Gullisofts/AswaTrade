<template>
    <div class="navigation">
        <ul>
            <li>
                <a class="open_hide_sidebar">
                    <span class="icon"><i class="mn-ico fas fa-bars"></i></span>
                </a>
            </li>
            <li>
                <router-link tag="a" to="/">
                    <span class="icon"><i class="mn-ico fas fa-home"></i></span>
                    <span class="title">الرئيسية</span>
                </router-link>
            </li>
            <li class="main_list">
                <a>
                    <span class="icon"><i class="mn-ico fas fa-box-open"></i></span>
                    <span class="title">المنتجات</span>
                    <span class="open_arrow"><i class="fas fa-caret-left"></i></span>
                </a>
                <ul class="inner_list">
                    <li>
                        <router-link tag="a" to="/addnewproduct">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">اضافة منتج</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link tag="a" to="/lowqty">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">منتجات بدون رصيد</span>
                        </router-link>
                    </li>
                    <li>
                            <router-link tag="a" to="/searchproduct">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">البحث عن منتج</span>
                        </router-link>
                    </li>
                </ul>
            </li>
            <li class="main_list">
                <a>
                    <span class="icon"><i class="mn-ico fa fa-list-alt"></i></span>
                    <span class="title">الأقسام</span>
                    <span class="open_arrow"><i class="fas fa-caret-left"></i></span>
                </a>
                <ul class="inner_list">
                    <li>
                        <router-link tag="a" to="/sections">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">عرض الأقسام</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link tag="a" to="/addnewsection">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">أضافة قسم جديد</span>
                        </router-link>
                    </li>
                </ul>
            </li>
            <li class="main_list">
                <a tag="a">
                    <span class="icon"><i class="mn-ico fa fa-shopping-cart"></i></span>
                    <span class="title">المبيعات</span>
                    <span class="open_arrow"><i class="fas fa-caret-left"></i></span>
                </a>
                <ul class="inner_list">
                    <li>
                        <router-link tag="a" to="/neworders">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">الطلبات الجديدة</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link tag="a" to="/prepareorders">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">طلبات التجهيز</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link tag="a" to="/searchshippment">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">البحث عن طلبات</span>
                        </router-link>
                    </li>
                </ul>
            </li>
            <li>
                <router-link tag="a" to="/salesrepo">
                    <span class="icon"><i class="mn-ico fas fa-chart-pie"></i></span>
                    <span class="title">التقارير</span>
                </router-link>
            </li>
            <li class="main_list">
                <a tag="a">
                    <span class="icon"><i class="mn-ico fas fa-users"></i></span>
                    <span class="title">العملاء</span>
                    <span class="open_arrow"><i class="fas fa-caret-left"></i></span>
                </a>
                <ul class="inner_list">
                    <li>
                        <router-link tag="a" to="/searchcustomer">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">البحث عن عميل</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link tag="a" to="/blockedusers">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">العملاء المحظورين</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link tag="a" to="/customercomments">
                            <span class="icon"><i class="fas fa-circle"></i></span>
                            <span class="title">التعليقات</span>
                        </router-link>
                    </li>
                </ul>
            </li>
            <li>
                <router-link tag="a" to="/support">
                    <span class="icon"><i class="mn-ico fas fa-life-ring"></i></span>
                    <span class="title">الدعم</span>
                </router-link>
            </li>
            <li>
                <router-link tag="a" to="/pages">
                    <span class="icon"><i class="mn-ico fas fa-file"></i></span>
                    <span class="title">الصفحات</span>
                </router-link>
            </li>
            <li>
                <router-link tag="a" to="/settings">
                    <span class="icon"><i class="mn-ico fas fa-cog"></i></span>
                    <span class="title">الإعدادات</span>
                </router-link>
            </li>
            <li>
                <a href="/" target="_blank">
                    <span class="icon"><i class="mn-ico fas fa-globe"></i></span>
                    <span class="title">رئيسية الموقع</span>
                </a>
            </li>
            <li>
                <a href="/admin_logout">
                    <span class="icon"><i class="mn-ico fas fa-globe"></i></span>
                    <span class="title">خروج</span>
                </a>
            </li>
        </ul>
    </div>

</template>
<script>
export default {

    name: "Dashsidebar",

    mounted()
    {
        $(document).on("click", ".main_list", function(event)
        {
            event.stopPropagation();
            event.stopImmediatePropagation();

            $(".navigation .main_list").find(".inner_list").slideUp();

            if(!$(this).find(".inner_list").is(':visible'))
            {
                $(this).find(".inner_list").slideDown();
            }
            else
            {
                $(this).find(".inner_list").slideUp();
            }

        });

        $(".navigation").height($(".dash_bod").height());
        
    },
}
</script>