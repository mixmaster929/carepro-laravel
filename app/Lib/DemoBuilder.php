<?php
namespace App\Lib;

use App\Article;
use App\Category;
use App\FooterMenu;
use App\HeaderMenu;
use App\Setting;
use App\Template;

class DemoBuilder
{

    public function run(){
        $this->articles();
        $this->categories();
        $this->headerMenu();
        $this->footerMenu();
        $this->settings();

        $this->templateOptionsBuson();


    }

    public function settings(){
        $setting= Setting::where('key','image_logo')->first();
        if($setting){
            $setting->value = 'img/demo/logo.png';
            $setting->save();
        }

        $setting= Setting::where('key','image_icon')->first();
        if($setting){
            $setting->value = 'img/demo/icon.png';
            $setting->save();
        }

        Setting::where('key','general_site_name')->update(['value'=>'My Site']);
        Setting::where('key','general_homepage_title')->update(['value'=>'My Agency']);

        Setting::where('key','general_admin_email')->update(['value'=>'info@email.com']);
        Setting::where('key','general_address')->update(['value'=>'234, Jersey Road']);
        Setting::where('key','general_tel')->update(['value'=>'08039485906']);
        Setting::where('key','general_contact_email')->update(['value'=>'info@email.com']);
    }

    public function articles(){
        Article::insert(
            [
                [
                    'title'=>'Who We Are',
                    'content'=>$this->dummyText(),
                    'slug'=>'who-we-are',
                    'status'=>1,
                    'meta_title'=>'Who We Are'
                ],
                [
                    'title'=>'Our Services',
                    'content'=>$this->dummyText(),
                    'slug'=>'our-services',
                    'status'=>1,
                    'meta_title'=>'Our Services'
                ],
                [
                    'title'=>'FAQ',
                    'content'=>$this->dummyText(),
                    'slug'=>'faq',
                    'status'=>1,
                    'meta_title'=>'FAQs'
                ]
            ]
        );
    }

    public function categories(){
        Category::insert([
           [
               'name'=>'Live In Nannies',
               'description'=>'Nannies who are willing to work and reside in your home',
               'sort_order'=>1,
               'public'=>1
           ],
            [
                'name'=>'Live Out Nannies',
                'description'=>'Nannies who will close and resume at your location',
                'sort_order'=>2,
                'public'=>1
            ]
        ]);
    }

    public function headerMenu(){
        HeaderMenu::truncate();

        HeaderMenu::insert([
            [
                'id'=>1,
                'name'=>'Home Page',
                'label'=>'Home',
                'url'=>'/',
                'type'=>'p',
                'sort_order'=>1,
                'parent_id'=>0
            ],
            [
                'id'=>2,
                'name'=>'Custom',
                'label'=>'Info',
                'url'=>'#',
                'type'=>'c',
                'sort_order'=>'2',
                'parent_id'=>0
            ]
        ]

        );

        //get articles
        $count = 1;
        foreach(Article::orderBy('title','desc')->get() as $article){
            HeaderMenu::insert([
                'name'=>'Article: '.$article->title,
                'label'=>$article->title,
                'url'=>'/'.$article->slug,
                'type'=>'a',
                'sort_order'=>$count,
                'parent_id'=>2
            ]);
            $count++;
        }

        $orderMenu = HeaderMenu::create([
           'name'=>'Custom',
            'label'=>'Order',
            'url'=>'/order-form',
            'type'=>'p',
            'sort_order'=>3,
            'parent_id'=>0
        ]);

        HeaderMenu::insert([
            [
                'name'=>'Order Form',
                'label'=>'Order Form',
                'url'=>'/order-form/1',
                'type'=>'p',
                'sort_order'=>1,
                'parent_id'=>$orderMenu->id
            ],
            [
                'name'=>'Profiles',
                'label'=>'Browse Profiles',
                'url'=>'/profiles',
                'type'=>'p',
                'sort_order'=>'2',
                'parent_id'=>$orderMenu->id
            ],
            [
                'name'=>'Vacancies',
                'label'=>'Vacancies',
                'url'=>'/vacancies',
                'type'=>'p',
                'sort_order'=>4,
                'parent_id'=>0
            ],
            [
                'name'=>'Blog',
                'label'=>'Blog',
                'url'=>'/blog',
                'type'=>'p',
                'sort_order'=>'5',
                'parent_id'=>0
            ],
            [
                'name'=>'Contact',
                'label'=>'Contact',
                'url'=>'/contact',
                'type'=>'p',
                'sort_order'=>'6',
                'parent_id'=>0
            ]
        ]

        );

    }

    public function footerMenu(){
        FooterMenu::truncate();

        FooterMenu::insert([
                [
                    'id'=>1,
                    'name'=>'Custom',
                    'label'=>'Info',
                    'url'=>'#',
                    'type'=>'c',
                    'sort_order'=>1,
                    'parent_id'=>0
                ],
                [
                    'id'=>2,
                    'name'=>'Custom',
                    'label'=>'Quick Links',
                    'url'=>'#',
                    'type'=>'c',
                    'sort_order'=>'2',
                    'parent_id'=>0
                ]
            ]

        );


        $count = 1;
        foreach(Article::orderBy('title','desc')->get() as $article){
            FooterMenu::insert([
                'name'=>'Article: '.$article->title,
                'label'=>$article->title,
                'url'=>'/'.$article->slug,
                'type'=>'a',
                'sort_order'=>$count,
                'parent_id'=>1
            ]);
            $count++;
        }

        FooterMenu::insert([
                [
                    'name'=>'Order Form',
                    'label'=>'Order Form',
                    'url'=>'/order-form/1',
                    'type'=>'p',
                    'sort_order'=>1,
                    'parent_id'=>2
                ],
                [
                    'name'=>'Profiles',
                    'label'=>'Browse Profiles',
                    'url'=>'/profiles',
                    'type'=>'p',
                    'sort_order'=>2,
                    'parent_id'=>2
                ],
                [
                    'name'=>'Vacancies',
                    'label'=>'Vacancies',
                    'url'=>'/vacancies',
                    'type'=>'p',
                    'sort_order'=>3,
                    'parent_id'=>2
                ],
                [
                    'name'=>'Blog',
                    'label'=>'Blog',
                    'url'=>'/blog',
                    'type'=>'p',
                    'sort_order'=>4,
                    'parent_id'=>2
                ],
                [
                    'name'=>'Contact',
                    'label'=>'Contact',
                    'url'=>'/contact',
                    'type'=>'p',
                    'sort_order'=>5,
                    'parent_id'=>2
                ]
            ]

        );


    }

    public function templateOptions(){
        Template::where('id','>',0)->update(['enabled'=>0]);
        //create template record
        $template = Template::where('directory','nunis')->first();
        if(!$template){
           $template= Template::create([
              'name'=>'Nunis',
               'enabled'=>1,
               'directory'=>'nunis'
           ]);
        }
        $template->enabled=1;
        $template->save();

        //now set options
        $template->templateOptions()->createMany([

            [
                'name'=>'navigation',
                'value'=>'a:5:{s:6:"_token";s:40:"XykvSUzb3uPZTzJqqTFLD3VfIzwVziE1oVZJciin";s:7:"enabled";s:1:"1";s:8:"bg_color";N;s:10:"font_color";N;s:12:"order_button";s:1:"1";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'homepage-about',
                'value'=>'a:9:{s:6:"_token";s:40:"8jnb6kBKNB2moNVBP322DvRN1xDSTpkha0PSlQ6w";s:7:"enabled";s:1:"1";s:11:"top_heading";s:8:"About Us";s:11:"sub_heading";s:25:"Your Premier Nanny Agency";s:7:"heading";s:10:"Who We Are";s:4:"text";s:456:"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<br></p>";s:11:"button_text";s:10:"Learn More";s:10:"button_url";s:11:"/who-we-are";s:5:"image";s:68:"img/demo/about-thumb.jpg";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'candidates',
                'value'=>'a:6:{s:6:"_token";s:40:"8jnb6kBKNB2moNVBP322DvRN1xDSTpkha0PSlQ6w";s:7:"enabled";s:1:"1";s:11:"sub_heading";s:8:"Profiles";s:7:"heading";s:11:"Our Nannies";s:15:"candidate_limit";s:2:"10";s:5:"order";s:1:"l";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'testimonials',
                'value'=>'a:28:{s:6:"_token";s:40:"8jnb6kBKNB2moNVBP322DvRN1xDSTpkha0PSlQ6w";s:7:"enabled";s:1:"1";s:11:"sub_heading";s:12:"Testimonials";s:7:"heading";s:16:"What Parents Say";s:5:"name1";s:7:"Shola A";s:5:"role1";s:3:"Mom";s:6:"image1";s:67:"img/demo/tes1.png";s:5:"text1";s:445:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";s:5:"name2";s:8:"Tolulope";s:5:"role2";s:3:"Dad";s:6:"image2";s:67:"img/demo/tes2.png";s:5:"text2";s:445:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";s:5:"name3";s:5:"Bunmi";s:5:"role3";s:3:"Mom";s:6:"image3";s:67:"img/demo/tes3.png";s:5:"text3";s:445:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";s:5:"name4";N;s:5:"role4";N;s:6:"image4";N;s:5:"text4";N;s:5:"name5";N;s:5:"role5";N;s:6:"image5";N;s:5:"text5";N;s:5:"name6";N;s:5:"role6";N;s:6:"image6";N;s:5:"text6";N;}',
                'enabled'=>'1',
            ],
            [
                'name'=>'blog',
                'value'=>'a:5:{s:6:"_token";s:40:"8jnb6kBKNB2moNVBP322DvRN1xDSTpkha0PSlQ6w";s:7:"enabled";s:1:"1";s:11:"sub_heading";s:4:"Blog";s:7:"heading";s:12:"Latest Posts";s:5:"limit";s:1:"3";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'footer-gallery',
                'value'=>'a:8:{s:6:"_token";s:40:"8jnb6kBKNB2moNVBP322DvRN1xDSTpkha0PSlQ6w";s:7:"enabled";s:1:"1";s:5:"file1";s:67:"img/demo/footer1.jpg";s:5:"file2";s:68:"img/demo/footer2.jpg";s:5:"file3";s:68:"img/demo/footer3.jpg";s:5:"file4";s:68:"img/demo/footer4.jpg";s:5:"file5";s:68:"img/demo/footer5.jpg";s:5:"file6";s:68:"img/demo/footer6.jpg";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'footer',
                'value'=>'a:13:{s:6:"_token";s:40:"8jnb6kBKNB2moNVBP322DvRN1xDSTpkha0PSlQ6w";s:7:"enabled";s:1:"1";s:4:"text";s:445:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";s:15:"newsletter-code";N;s:7:"credits";s:20:"© 2020 Nanny Agency";s:8:"bg_color";N;s:10:"font_color";N;s:5:"image";N;s:15:"social_facebook";s:1:"#";s:14:"social_twitter";s:1:"#";s:16:"social_instagram";s:1:"#";s:14:"social_youtube";s:1:"#";s:15:"social_linkedin";s:1:"#";}',
                'enabled'=>'1',
            ]
        ]);

    }

    public function templateOptionsBuson(){
        Template::where('id','>',0)->update(['enabled'=>0]);
        //create template record
        $template = Template::where('directory','buson')->first();
        if(!$template){
            $template= Template::create([
                'name'=>'Buson',
                'enabled'=>1,
                'directory'=>'buson'
            ]);
        }
        $template->enabled=1;
        $template->save();

        //now set options
        $template->templateOptions()->createMany([
            [
                'name'=>'top-bar',
                'value'=>'a:11:{s:6:"_token";s:40:"FJCotq9TzWxE17MEEaYWfJhLXyEwDDMqsF9bglnK";s:7:"enabled";s:1:"1";s:14:"office_address";s:16:"234, Jersey Road";s:5:"email";s:14:"info@email.com";s:8:"bg_color";N;s:10:"font_color";N;s:15:"social_facebook";s:1:"#";s:14:"social_twitter";s:1:"#";s:16:"social_instagram";s:1:"#";s:14:"social_youtube";s:1:"#";s:15:"social_linkedin";s:1:"#";}',
                'enabled'=>1
            ],
            [
                'name'=>'navigation',
                'value'=>'a:5:{s:6:"_token";s:40:"FJCotq9TzWxE17MEEaYWfJhLXyEwDDMqsF9bglnK";s:7:"enabled";s:1:"1";s:8:"bg_color";N;s:10:"font_color";N;s:12:"order_button";s:1:"1";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'slideshow',
                'value'=>'a:72:{s:6:"_token";s:40:"gCOeCtTPQaESMEVTShGLb7rbvYf3hAPnbYXSSdm0";s:7:"enabled";s:1:"1";s:5:"file1";s:68:"img/demo/slide1.jpg";s:14:"slide_heading1";s:27:"Professional Nanny Services";s:19:"heading_font_color1";N;s:11:"slide_text1";s:33:"Get the best childcare providers!";s:16:"text_font_color1";N;s:12:"button_text1";s:10:"Learn More";s:4:"url1";s:1:"#";s:5:"file2";s:68:"img/demo/slide2.jpg";s:14:"slide_heading2";s:26:"Housekeeping Professionals";s:19:"heading_font_color2";N;s:11:"slide_text2";s:36:"Get housekeeping staff for your home";s:16:"text_font_color2";N;s:12:"button_text2";s:10:"Learn More";s:4:"url2";s:1:"#";s:5:"file3";N;s:14:"slide_heading3";N;s:19:"heading_font_color3";N;s:11:"slide_text3";N;s:16:"text_font_color3";N;s:12:"button_text3";N;s:4:"url3";N;s:5:"file4";N;s:14:"slide_heading4";N;s:19:"heading_font_color4";N;s:11:"slide_text4";N;s:16:"text_font_color4";N;s:12:"button_text4";N;s:4:"url4";N;s:5:"file5";N;s:14:"slide_heading5";N;s:19:"heading_font_color5";N;s:11:"slide_text5";N;s:16:"text_font_color5";N;s:12:"button_text5";N;s:4:"url5";N;s:5:"file6";N;s:14:"slide_heading6";N;s:19:"heading_font_color6";N;s:11:"slide_text6";N;s:16:"text_font_color6";N;s:12:"button_text6";N;s:4:"url6";N;s:5:"file7";N;s:14:"slide_heading7";N;s:19:"heading_font_color7";N;s:11:"slide_text7";N;s:16:"text_font_color7";N;s:12:"button_text7";N;s:4:"url7";N;s:5:"file8";N;s:14:"slide_heading8";N;s:19:"heading_font_color8";N;s:11:"slide_text8";N;s:16:"text_font_color8";N;s:12:"button_text8";N;s:4:"url8";N;s:5:"file9";N;s:14:"slide_heading9";N;s:19:"heading_font_color9";N;s:11:"slide_text9";N;s:16:"text_font_color9";N;s:12:"button_text9";N;s:4:"url9";N;s:6:"file10";N;s:15:"slide_heading10";N;s:20:"heading_font_color10";N;s:12:"slide_text10";N;s:17:"text_font_color10";N;s:13:"button_text10";N;s:5:"url10";N;}',
                'enabled'=>'1',
            ],
            [
                'name'=>'homepage-services',
                'value'=>'a:12:{s:6:"_token";s:40:"gCOeCtTPQaESMEVTShGLb7rbvYf3hAPnbYXSSdm0";s:7:"enabled";s:1:"1";s:5:"file1";s:68:"img/demo/service1.jpg";s:8:"heading1";s:14:"Nanny Services";s:5:"text1";s:129:"<p>We provide the best Nanny services in the industry. Our candidates are well trained to international standards.</p><p><br></p>";s:5:"file2";s:68:"img/demo/service2.jpg";s:8:"heading2";s:18:"Screening Services";s:5:"text2";s:176:"We perform screening and background checks on potential and existing employees. Screening services include health screening, criminal records, information verification etc.<br>";s:12:"info_heading";s:23:"The Best Nanny Services";s:9:"info_text";s:167:"<p>Our agency is an accredited and professional agency with over 10 years experience. We are good at what we do and a trial will certainly convince you!&nbsp; <br></p>";s:11:"button_text";s:10:"Learn More";s:3:"url";s:1:"#";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'homepage-about',
                'value'=>'a:7:{s:6:"_token";s:40:"gCOeCtTPQaESMEVTShGLb7rbvYf3hAPnbYXSSdm0";s:7:"enabled";s:1:"1";s:7:"heading";s:28:"1000 Clients and counting...";s:4:"text";s:318:"<p>Over the years, we are proud to have serviced more than 1000 satisfied clients! Our client list is spread across all states of the federation. </p><p>Some of our clients include:</p><ol><li>Supertech Limited</li><li>Super Schools Limited</li><li>Andre Montessori School</li><li>Kings Elementary School<br></li></ol>";s:11:"button_text";s:9:"Read more";s:10:"button_url";s:1:"#";s:5:"image";s:68:"img/demo/about.jpg";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'candidates',
                'value'=>'a:6:{s:6:"_token";s:40:"8jnb6kBKNB2moNVBP322DvRN1xDSTpkha0PSlQ6w";s:7:"enabled";s:1:"1";s:11:"sub_heading";s:8:"Profiles";s:7:"heading";s:11:"Our Nannies";s:15:"candidate_limit";s:2:"10";s:5:"order";s:1:"l";}',
                'enabled'=>'0',
            ],
            [
                'name'=>'testimonials',
                'value'=>'a:28:{s:6:"_token";s:40:"8jnb6kBKNB2moNVBP322DvRN1xDSTpkha0PSlQ6w";s:7:"enabled";s:1:"1";s:11:"sub_heading";s:12:"Testimonials";s:7:"heading";s:16:"What Parents Say";s:5:"name1";s:7:"Shola A";s:5:"role1";s:3:"Mom";s:6:"image1";s:67:"img/demo/tes1.png";s:5:"text1";s:445:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";s:5:"name2";s:8:"Tolulope";s:5:"role2";s:3:"Dad";s:6:"image2";s:67:"img/demo/tes2.png";s:5:"text2";s:445:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";s:5:"name3";s:5:"Bunmi";s:5:"role3";s:3:"Mom";s:6:"image3";s:67:"img/demo/tes3.png";s:5:"text3";s:445:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";s:5:"name4";N;s:5:"role4";N;s:6:"image4";N;s:5:"text4";N;s:5:"name5";N;s:5:"role5";N;s:6:"image5";N;s:5:"text5";N;s:5:"name6";N;s:5:"role6";N;s:6:"image6";N;s:5:"text6";N;}',
                'enabled'=>'1',
            ],
            [
                'name'=>'blog',
                'value'=>'a:5:{s:6:"_token";s:40:"8jnb6kBKNB2moNVBP322DvRN1xDSTpkha0PSlQ6w";s:7:"enabled";s:1:"1";s:11:"sub_heading";s:4:"Blog";s:7:"heading";s:12:"Latest Posts";s:5:"limit";s:1:"3";}',
                'enabled'=>'0',
            ],
            [
                'name'=>'contact-form',
                'value'=>'a:6:{s:6:"_token";s:40:"gCOeCtTPQaESMEVTShGLb7rbvYf3hAPnbYXSSdm0";s:7:"enabled";s:1:"1";s:7:"heading";s:12:"Get in touch";s:4:"text";s:129:"<p>Do you have any questions about our services? Get in touch with us now and we will be glad to get back to you shortly.<br></p>";s:8:"bg_color";N;s:10:"font_color";N;}',
                'enabled'=>'1',
            ],
            [
                'name'=>'footer',
                'value'=>'a:13:{s:6:"_token";s:40:"FJCotq9TzWxE17MEEaYWfJhLXyEwDDMqsF9bglnK";s:7:"enabled";s:1:"1";s:4:"text";s:60:"We are the best agency for all your domestic staffing needs!";s:15:"newsletter-code";N;s:7:"credits";s:20:"© 2020 Nanny Agency";s:8:"bg_color";N;s:10:"font_color";N;s:5:"image";N;s:15:"social_facebook";s:1:"#";s:14:"social_twitter";s:1:"#";s:16:"social_instagram";s:1:"#";s:14:"social_youtube";s:1:"#";s:15:"social_linkedin";s:1:"#";}',
                'enabled'=>'1',
            ]
        ]);

    }

    private function dummyText(){
        return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    }

    public function templateOptionsJobPortal(){
        Template::where('id','>',0)->update(['enabled'=>0]);
        //create template record
        $template = Template::where('directory','jobportal')->first();
        if(!$template){
            $template= Template::create([
                'name'=>'Job Portal',
                'enabled'=>1,
                'directory'=>'jobportal'
            ]);
        }
        $template->enabled=1;
        $template->save();

        //now set options
        $template->templateOptions()->createMany([

            [
                'name'=>'navigation',
                'value'=>'a:5:{s:6:"_token";s:40:"XykvSUzb3uPZTzJqqTFLD3VfIzwVziE1oVZJciin";s:7:"enabled";s:1:"1";s:8:"bg_color";N;s:10:"font_color";N;s:12:"order_button";s:1:"1";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'slideshow',
                'value'=>'a:92:{s:6:"_token";s:40:"1VXSUGvIUaebrfSspB0AHV9cKZ7Gf6Rj3dUzllSF";s:7:"enabled";s:1:"1";s:5:"file1";s:67:"templates/jobportal/assets/demo/images/parent_child3.png";s:14:"slide_heading1";s:27:"Professional Nanny Services";s:19:"heading_font_color1";N;s:11:"slide_text1";s:139:"Get the best childcare providers for your home. Our Nannies are professionally trained and we have a wide selection for you to select from!";s:16:"text_font_color1";N;s:15:"button_1_text_1";s:15:"Browse Profiles";s:7:"url_1_1";s:9:"/profiles";s:15:"button_2_text_1";s:9:"Order Now";s:7:"url_2_1";s:12:"/order-forms";s:5:"file2";s:67:"templates/jobportal/assets/demo/images/nanny.png";s:14:"slide_heading2";s:49:"Find the best childcare Jobs. Get employed today!";s:19:"heading_font_color2";N;s:11:"slide_text2";s:126:"Our agency connects you with the best paying childcare jobs in your area. Signup today and get started with a rewarding career";s:16:"text_font_color2";N;s:15:"button_1_text_2";s:10:"Signup Now";s:7:"url_1_2";s:19:"/register/candidate";s:15:"button_2_text_2";s:11:"Browse Jobs";s:7:"url_2_2";s:10:"/vacancies";s:5:"file3";N;s:14:"slide_heading3";N;s:19:"heading_font_color3";N;s:11:"slide_text3";N;s:16:"text_font_color3";N;s:15:"button_1_text_3";N;s:7:"url_1_3";N;s:15:"button_2_text_3";N;s:7:"url_2_3";N;s:5:"file4";N;s:14:"slide_heading4";N;s:19:"heading_font_color4";N;s:11:"slide_text4";N;s:16:"text_font_color4";N;s:15:"button_1_text_4";N;s:7:"url_1_4";N;s:15:"button_2_text_4";N;s:7:"url_2_4";N;s:5:"file5";N;s:14:"slide_heading5";N;s:19:"heading_font_color5";N;s:11:"slide_text5";N;s:16:"text_font_color5";N;s:15:"button_1_text_5";N;s:7:"url_1_5";N;s:15:"button_2_text_5";N;s:7:"url_2_5";N;s:5:"file6";N;s:14:"slide_heading6";N;s:19:"heading_font_color6";N;s:11:"slide_text6";N;s:16:"text_font_color6";N;s:15:"button_1_text_6";N;s:7:"url_1_6";N;s:15:"button_2_text_6";N;s:7:"url_2_6";N;s:5:"file7";N;s:14:"slide_heading7";N;s:19:"heading_font_color7";N;s:11:"slide_text7";N;s:16:"text_font_color7";N;s:15:"button_1_text_7";N;s:7:"url_1_7";N;s:15:"button_2_text_7";N;s:7:"url_2_7";N;s:5:"file8";N;s:14:"slide_heading8";N;s:19:"heading_font_color8";N;s:11:"slide_text8";N;s:16:"text_font_color8";N;s:15:"button_1_text_8";N;s:7:"url_1_8";N;s:15:"button_2_text_8";N;s:7:"url_2_8";N;s:5:"file9";N;s:14:"slide_heading9";N;s:19:"heading_font_color9";N;s:11:"slide_text9";N;s:16:"text_font_color9";N;s:15:"button_1_text_9";N;s:7:"url_1_9";N;s:15:"button_2_text_9";N;s:7:"url_2_9";N;s:6:"file10";N;s:15:"slide_heading10";N;s:20:"heading_font_color10";N;s:12:"slide_text10";N;s:17:"text_font_color10";N;s:16:"button_1_text_10";N;s:8:"url_1_10";N;s:16:"button_2_text_10";N;s:8:"url_2_10";N;}',
                'enabled'=>'1',
            ],
            [
                'name'=>'order-prompt',
                'value'=>'a:3:{s:6:"_token";s:40:"1VXSUGvIUaebrfSspB0AHV9cKZ7Gf6Rj3dUzllSF";s:7:"enabled";s:1:"1";s:4:"text";s:47:"Find the perfect Nanny! Place your order today.";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'homepage-about',
                'value'=>'a:8:{s:6:"_token";s:40:"1VXSUGvIUaebrfSspB0AHV9cKZ7Gf6Rj3dUzllSF";s:7:"enabled";s:1:"1";s:7:"heading";s:50:"Your precious kids deserve the Best Nanny Services";s:4:"text";s:776:"<p>Our agency is an accredited and professional agency with over 10 years experience. We are good at what we do and a trial will certainly convince you!  </p><p>Some of our services include:</p> <br> <p> <strong>Nanny Services</strong><br> We provide the best Nanny services in the industry. Our candidates are well trained to international standards.</p> <br> <p> <strong>Screening Services</strong><br> We perform screening and background checks on potential and existing employees. Screening services include health screening, criminal records, information verification etc.</p> <br> <p> <strong>Training</strong><br> We provide training for nannies and babysitters. Training includes first aid, fire safety, infant care, nutrition and much more!<br></p>";s:7:"image_1";s:67:"templates/jobportal/assets/demo/images/about1.png";s:7:"image_2";s:67:"templates/jobportal/assets/demo/images/about2.png";s:7:"image_3";s:67:"templates/jobportal/assets/demo/images/about3.png";s:7:"image_4";s:67:"templates/jobportal/assets/demo/images/about4.png";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'candidates',
                'value'=>'a:7:{s:6:"_token";s:40:"1VXSUGvIUaebrfSspB0AHV9cKZ7Gf6Rj3dUzllSF";s:7:"enabled";s:1:"1";s:11:"sub_heading";s:18:"Candidate Profiles";s:7:"heading";s:19:"Our Amazing Nannies";s:4:"text";s:63:"Browse through some of our professional Nannies and Babysitters";s:15:"candidate_limit";s:1:"8";s:5:"order";s:1:"l";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'candidate-prompt',
                'value'=>'a:6:{s:6:"_token";s:40:"1VXSUGvIUaebrfSspB0AHV9cKZ7Gf6Rj3dUzllSF";s:7:"enabled";s:1:"1";s:11:"sub_heading";s:22:"PROSPECTIVE CANDIDATES";s:7:"heading";s:46:"Get Started on the path to a rewarding career!";s:4:"text";s:112:"Join our agency and get your dream job in the childcare industry. We guarantee you a great placement experience!";s:8:"bg_color";N;}',
                'enabled'=>'1',
            ],[
                'name'=>'vacancies',
                'value'=>'a:6:{s:6:"_token";s:40:"1VXSUGvIUaebrfSspB0AHV9cKZ7Gf6Rj3dUzllSF";s:7:"enabled";s:1:"1";s:11:"sub_heading";s:9:"Vacancies";s:7:"heading";s:18:"Browse Recent Jobs";s:4:"text";s:61:"Here are some available vacancies from our Agency. Apply now!";s:5:"limit";s:1:"8";}',
                'enabled'=>'1',
            ], [
                'name'=>'testimonials',
                'value'=>'a:29:{s:6:"_token";s:40:"1VXSUGvIUaebrfSspB0AHV9cKZ7Gf6Rj3dUzllSF";s:7:"enabled";s:1:"1";s:11:"sub_heading";s:20:"What our clients say";s:7:"heading";s:12:"Testimonials";s:5:"image";s:67:"templates/jobportal/assets/demo/images/parent_child3.png";s:5:"name1";s:8:"John doe";s:5:"role1";s:6:"Parent";s:6:"image1";s:67:"templates/jobportal/assets/demo/testimonial/testi4.jpg";s:5:"text1";s:65:"I had an amazing experience with this agency. I highly recommend!";s:5:"name2";s:12:"Mary Chapman";s:5:"role2";s:5:"Nanny";s:6:"image2";s:67:"templates/jobportal/assets/demo/testimonial/testi3.jpg";s:5:"text2";s:41:"They got me my dream job! Thank you guys!";s:5:"name3";N;s:5:"role3";N;s:6:"image3";N;s:5:"text3";N;s:5:"name4";N;s:5:"role4";N;s:6:"image4";N;s:5:"text4";N;s:5:"name5";N;s:5:"role5";N;s:6:"image5";N;s:5:"text5";N;s:5:"name6";N;s:5:"role6";N;s:6:"image6";N;s:5:"text6";N;}',
                'enabled'=>'1',
            ],[
                'name'=>'footer-top',
                'value'=>'a:6:{s:6:"_token";s:40:"MraYpqzdZU0eijvwqzdq28jrfBWYzpIsAsFSksTJ";s:7:"enabled";s:1:"1";s:7:"heading";s:23:"Employers, Get Started!";s:4:"text";s:47:"Find the perfect Nanny! Place your order today.";s:12:"order_button";s:1:"1";s:14:"profile_button";s:1:"1";}',
                'enabled'=>'1',
            ],
            [
                'name'=>'clients',
                'value'=>'a:11:{s:6:"_token";s:40:"MraYpqzdZU0eijvwqzdq28jrfBWYzpIsAsFSksTJ";s:7:"enabled";s:1:"1";s:5:"file1";s:67:"templates/jobportal/assets/demo/clients/client1.png";s:5:"file2";s:67:"templates/jobportal/assets/demo/clients/client2.png";s:5:"file3";s:67:"templates/jobportal/assets/demo/clients/client3.png";s:5:"file4";s:67:"templates/jobportal/assets/demo/clients/client4.png";s:5:"file5";s:67:"templates/jobportal/assets/demo/clients/client5.png";s:5:"file6";s:67:"templates/jobportal/assets/demo/clients/client6.png";s:5:"file7";N;s:5:"file8";N;s:5:"file9";N;}',
                'enabled'=>'1',
            ]
            ,
            [
                'name'=>'footer',
                'value'=>'a:12:{s:6:"_token";s:40:"MraYpqzdZU0eijvwqzdq28jrfBWYzpIsAsFSksTJ";s:7:"enabled";s:1:"1";s:4:"text";s:149:"Our agency is an accredited and professional agency with over 10 years experience. We are good at what we do and a trial will certainly convince you!";s:7:"credits";s:20:"© 2020 Nanny Agency";s:8:"bg_color";N;s:10:"font_color";N;s:5:"image";N;s:15:"social_facebook";s:1:"#";s:14:"social_twitter";s:1:"#";s:16:"social_instagram";s:1:"#";s:14:"social_youtube";s:1:"#";s:15:"social_linkedin";s:1:"#";}',
                'enabled'=>'1',
            ] ,
            [
                'name'=>'contact-page',
                'value'=>'a:11:{s:6:"_token";s:40:"MraYpqzdZU0eijvwqzdq28jrfBWYzpIsAsFSksTJ";s:7:"enabled";s:1:"1";s:7:"heading";s:19:"Contact information";s:4:"text";s:62:"Get in touch with us now! We look forward to hearing from you.";s:10:"google_map";s:1:"1";s:11:"map_address";s:23:"2880 Broadway, New York";s:15:"social_facebook";s:1:"#";s:14:"social_twitter";s:1:"#";s:16:"social_instagram";s:1:"#";s:14:"social_youtube";s:1:"#";s:15:"social_linkedin";s:1:"#";}',
                'enabled'=>'1',
            ] ,
            [
                'name'=>'page-title',
                'value'=>'a:5:{s:6:"_token";s:40:"MraYpqzdZU0eijvwqzdq28jrfBWYzpIsAsFSksTJ";s:7:"enabled";s:1:"1";s:5:"image";s:67:"templates/jobportal/assets/demo/images/page-title.jpg";s:8:"bg_color";N;s:10:"font_color";N;}',
                'enabled'=>'1',
            ]

        ]);

    }

}
