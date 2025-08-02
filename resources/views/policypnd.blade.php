<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{$company->companyname ?? "Payin Do"}} - Neo Banking</title>
    <link rel="shortcut icon" href="{{asset('')}}/frontassets/images/favicon.png" type="image/x-icon">
    <!--====== Google Fonts ======-->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;500;600;700&amp;family=Oswald:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    
    <!--====== Font Awesome ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/font-awesome-5.9.0.css">
    <!--====== Bootstrap ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/bootstrap.min.css">
    <!--====== Magnific Popup ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/magnific-popup.css">
    <!--====== Falticon ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/flaticon.css">
    <!--====== Animate ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/animate.css">
    <!--====== Slick ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/slick.css">
    <!--====== Main Style ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>
    <div class="page-wrapper">
        <section class="about-section style-four py-120 rpy-100">
            <div class="container rpb-95">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="about-content pr-90 rpr-0 rmb-55 wow fadeInLeft delay-0-2s" style="padding-top: 20px;">
                            <nav id="navbar-example2" class="navbar navbar-light bg-dark px-3 shadow" style="border-radius: 5px;" >
                                        @if ($mydata['company']->logo)
                                            <a class="navbar-brand no-padding" href="{{route('home')}}">
                                                <img src="{{asset('')}}public/{{$mydata['company']->logo}}" style="height:50px; width:180px; border-radius: 5px;" class=" img-responsive" alt="">
                                            </a>
                                        @else
                                            <a class="navbar-brand" href="{{route('home')}}" style="padding: 17px">
                                                <span class="companyname" style="color: black">{{$mydata['company']->companyname}}</span>
                                            </a>
                                        @endif
                                     <!--<a class="navbar-brand font-weight-bold text-white " href="#">Raj Pay Pvt. Ltd. </a>-->
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link font-weight-bold text-white" href="#about_us">About Us </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link font-weight-bold text-white" href="#terms_of_use">Terms of Use
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link font-weight-bold text-white" href="#privacy_policy">Privacy & Policy
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-offset="0" class="scrollspy-example bg-light shadow p-3 mb-5 rounded" tabindex="0" style="padding-left: 20px; padding-right: 20px; padding-top:20px; padding-bottom: 20px; text-align: justify;">
                                <h4 id="about_us"><u>ABOUT US</u></h4>
                                      <p class="d-block w-100">
                                         Raj Pay is a state-of-the-art B2B2C Digital Platform, under the parent SaaS based Company Gyanraj Enterprise registered under the Start-up India program.
                                      </p>
                                      <p class="d-block w-100">
                                          We are a 05-year-old company currently focused on developing cutting edge technology to enable Fintech solutions. Our primary goal is to make India financially inclusive by catering to scarcity of financial connectivity in tier II/III cities of India. The organisation has previously served in sectors of electronics, telecommunications, networking and e-commerce.
                                      </p>
                                      <p class="d-block w-100">
                                        We, at Raj Pay, enable local retail stores to offer Assisted Digital Financial Services like Cash Deposit, Cash Withdrawal, Balance Inquiry, Bill Payments, Micro ATM, Aadhaar Enabled Services (AEPS), DTH-Mobile Recharges, POS Services, SMS Payment, Insurance, Money Transfer etc.
                                      </p>
                                      <p class="d-block w-100">
                                        We have leveraged artificial intelligence in our technology to make these financial transactions seamless, quick and simple. We continuously keep iterating our solutions and adapting changes as per feedback of our 35,000-active partner-network to develop this secure platform which offers a significantly superior user experience
                                      </p>
                                      <p class="d-block w-100">
                                        Every day we are inching closer to our goal of transforming tier II/III stores into a one-stop solution for all digital and financial services. These stores will act as an enabler to digitise cash for the customers visiting their outlets thereby empowering greater financial connectivity in semi urban and rural regions across India. We are working on enhancing the portfolio of our authorised partners by continuously adding new services where they earn maximum income with minimum investment.
                                      </p>
                                <h4 id="terms_of_use"><u>TERMS OF USE</u></h4>
                                      <p class="d-block w-100">
                                        PLEASE READ THESE TERMS CAREFULLY BEFORE USING THIS SITE:
                                      </p>
                                      <p class="d-block w-100">
                                         This web site (www.Raj Pay.in) is published and maintained by Raj Pay, a professionally managed company, headquartered at 116/193 Kaiserbagh Lucknow - 226001. Raj Pay provides services to you subject to the following conditions. If you visit or do any transaction within this website, you accept these conditions. Please read them carefully.
                                      </p>
                                      <p class="d-block w-100">
                                       THIS TERMS AND CONDITIONS IS AN ELECTRONIC RECORD IN THE FORM OF AN ELECTRONIC CONTRACT FORMED UNDER THE INFORMATION TECHNOLOGY ACT, 2000 AND THE RULES MADE THEREUNDER AND THE AMENDED PROVISIONS PERTAINING TO ELECTRONIC DOCUMENTS / RECORDS IN VARIOUS STATUTES AS AMENDED BY THE INFORMATION TECHNOLOGY ACT, 2000. THIS DOCUMENT IS PUBLISHED AND SHALL BE CONSTRUED IN ACCORDANCE WITH THE PROVISIONS OF THE INFORMATION TECHNOLOGY (REASONABLE SECURITY PRACTICES AND PROCEDURES AND SENSITIVE PERSONAL DATA OF INFORMATION) RULES, 2011 UNDER INFORMATION TECHNOLOGY ACT, 2000; THAT REQUIRE PUBLISHING OF THE PRIVACY POLICY FOR COLLECTION, USE, STORAGE AND TRANSFER OF SENSITIVE PERSONAL DATA OR INFORMATION. PLEASE READ THIS TERMS AND CONDITIONS CAREFULLY BEFORE YOU CLICK ON “I AGREED”. BY USING THE WEBSITE, YOU INDICATE THAT YOU UNDERSTAND, AGREE AND CONSENT TO THIS TERMS AND CONDITIONS. IF YOU DO NOT AGREE WITH THE TERMS OF THIS WEBSITE, PLEASE DO NOT USE THIS WEBSITE. YOU HEREBY PROVIDE YOUR UNCONDITIONAL CONSENT OR TERMS AND CONDITIONS S TO “Raj Pay” AS PROVIDED UNDER SECTION 43A AND SECTION 72A OF INFORMATION TECHNOLOGY ACT, 2000.
                                      </p>

                                      <h6>SOFTWARE:</h6>
                                      <p class="d-block w-100">
                                        Any software, including any files, usages generated by the software, code, and data accompanying the software (collectively, "Software"), used or accessible through this Site may be used by you solely for accessing and using this Site for purposes expressly stated on the Site, provided that such uses are not competitive with or derogatory to Raj Pay retains full and complete title to and all intellectual property rights in the Software. You agree not to copy, distribute, sell, modify, decompile, reverse engineer, disassemble or create derivative works from any Software.
                                      </p>
                                      <h6>USER CONDUCT:</h6>
                                      <p class="d-block w-100">
                                        In using this Site, you agree:<br>
                                        Not to disrupt or interfere with the security of, or otherwise abuse, the Site, or any services, system resources, accounts, servers or networks connected to or accessible through the Site or affiliated or linked Web sites; Not to disrupt or interfere with any other user's enjoyment of the Site or affiliated or linked Web sites; Not to upload, post, or otherwise transmit through or on this Site any viruses or other harmful, disruptive or destructive files; Not to use or attempt to use another's account, service or system without authorization from Raj Pay, or create or use a false identity on this Site; Not to transmit through or on this Site spam, chain letters, junk mail or any other type of unsolicited mass email to people or entities who have not agreed to be part of such mailings; Not to share, your username and password to others either on or off the Site; Not to attempt to obtain unauthorised access to the Site or portions of the Site which are restricted from Raj Pay access; and, In addition, you agree that you are solely responsible for actions and communications undertaken or transmitted under your account, and that you will comply with all applicable local, state, national and international laws and regulations, including India, that relate to your use of or activities on this Site. This Site is controlled and operated in India. If you are in a jurisdiction which restricts you from accessing this Site, do not access or use this Site. Raj Pay makes no representation that the Site is appropriate or available for use outside India. 
                                      </p>
                                      <h6>PRIVACY POLICY:</h6>
                                      <p class="d-block w-100">
                                        You acknowledged Raj Pay may use the data collected in the course of our relationship for the purposes identified in our Privacy Policy, which is incorporated by reference as if fully set forth in these Terms of Use.
                                      </p>
                                      <h6>INFORMATION SECURITY:</h6>
                                      <p class="d-block w-100">
                                        For Your Personal Information we use proper security measures and apply ISO 270001: 2013 Standard which assures you we follow the entire standard and it gives you assurance that we keep your information safe and secure.  
                                      </p>
                                      <h6>LINKS AND THIRD-PARTY CONTENT:</h6>
                                      <p class="d-block w-100">
                                        This Site may from time to time contain links to other Websites. These links are provided as a convenience and do not constitute an endorsement, sponsorship or recommendation by Raj Pay, of or responsibility for the linked Web sites or any content, services or products available on or through such sites.
                                      </p>
                                      <h6>Links from Other Web Sites:</h6>
                                      <p class="d-block w-100">
                                        All links to this Site must be approved in writing by Raj Pay, except that Raj Pay, consents to links in which:
                                        The link is a text-only link containing only the title of the home page of this Site, The link "points" only to the home pages of the Site and not to deeper pages, The link, when activated by a user, displays this home page of the Site full-screen and not within a "frame" on the linked Web site, and The appearance, position, and other aspects of the link do not.
                                      </p>
                                      <h6>THIRD PARTY CONTENT:</h6>
                                      <p class="d-block w-100">
                                        This Site may from time to time contain material, data or information provided, posted or offered by third parties, including but not limited to advertisements or postings in online community discussions. You agree that neither Raj Pay, nor its affiliates shall have any liability whatsoever to you for any such third-party material, data or information.
                                        This site and the content available through it are provided on an "as is'' and "as available" basis. You expressly agree that use of this site and/or its content is at your sole risk. to the fullest extent permissible pursuant to applicable law, Raj Pay, and its affiliates disclaim all warranties of any kind, whether express or implied, including without limitation any warranty of merchantability, fitness for a particular purpose or non-infringement. You expressly agree that use of this site, including all content, data or software distributed by, downloaded or accessed from or through the site, is at your sole risk. You understand and agree that you will be solely responsible for any Raj Pay to your business, your computer system or loss of data that results from the download of such content, data and/or software or equipment you acknowledge Raj Pay, that does not control information, products or services offered by third parties through the site. except as otherwise agreed in writing, and its affiliates assume no responsibility for and make no warranty or representation as to the accuracy, currency, completeness, reliability or usefulness of any advice, opinion, statement or other content or of any products or services distributed or made available by third parties through the site. does not make any warranty that this site or its content will meet your requirements, or that this site or its content will be uninterrupted, timely, secure, or error free, or that defects, if any, will be corrected. nor does it make any warranty as to the results that may be obtained from use of this site or its content or as to the accuracy, completeness or reliability of any information obtained through use of this site.
                                        Raj Pay, ASSUMES NO RESPONSIBILITY FOR ANY LOSS SUFFERED BY A USER, INCLUDING, BUT NOT LIMITED TO, LOSS OF DATA FROM DELAYS, NON DELIVERIES OF CONTENT OR EMAIL, ERRORS, SYSTEM DOWN TIME, MISDELIVERIES OF CONTENT OR EMAIL, NETWORK OR SYSTEM OUT Raj Pay’S, FILE CORRUPTION, OR SERVICE INTERRUPTIONS CAUSED BY THE NEGLIGENCE Raj Pay OF Raj Pay , ITS AFFILIATES, ITS LICENSORS, OR A USER'S OWN ERRORS AND/OR OMISSIONS EXCEPT AS SPECIFICALLY PROVIDED HEREIN, Raj Pay DISCLAIMS ANY WARRANTY OR REPRESENTATION THAT CONFIDENTIALITY OF INFORMATION TRANSMITTED THROUGH THE SITE WILL BE MAINTAINED.
                                      </p>
                                      <h6>LIMITATION OF LIABILITY:</h6>
                                      <p class="d-block w-100">
                                        UNDER NO CIRCUMSTANCES, INCLUDING, WITHOUT LIMITATION, Raj Pay SHALL OR ITS PARENTS, SUBSIDIARIES, AFFILIATES, OFFICERS, DIRECTORS, EMPLOYEES, OR SUPPLIERS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL OR CONSEQUENTIAL DAMAGES ARISING FROM OR IN CONNECTION WITH THE USE OF OR THE INABILITY TO USE THIS SITE OR ANY CONTENT CONTAINED ON THE SITE, OR RESULTING FROM UNAUTHORISED ACCESS TO OR ALTERATION OF YOUR TRANSMISSIONS OR DATA, OR OTHER INFORMATION THAT IS SENT OR RECEIVED OR NOT SENT OR RECEIVED, INCLUDING BUT NOT LIMITED TO, DAMAGES FOR LOSS OF PROFITS, USE, DATA OR OTHER INTANGIBLES, EVEN IF Raj Pay HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES THE PARTIES ACKNOWLEDGE THAT THIS IS A REASONABLE ALLOCATION OF RISK. SOME JURISDICTIONS DO NOT ALLOW THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES, SO SOME OF THE ABOVE MAY NOT APPLY TO YOU
                                      </p>
                                      <h6>Raj Pay APPLICABLE LAW:</h6>
                                      <p class="d-block w-100">
                                        These Terms of Use shall be governed by and construed in accordance with the laws of India. You agree to submit to the personal and exclusive jurisdiction of the Indian courts located within Lucknow, India. These terms constitute the entire Terms and conditions between you and Raj Pay, governing your use of the Site. Should any provision in these terms be found invalid or unenforceable for any reason, then that provision shall be deemed severable from the terms and shall not affect the validity or enforceability of the remaining provisions. You agree that any claim arising out of or related to the terms or your use of the Site must be filed within one year after it arose or be permanently barred.
                                      </p>
                                      <h6>ELECTRONIC COMMUNICATIONS:</h6>
                                      <p class="d-block w-100">
                                        When you visit Raj Pay or send emails to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by e-mail or by posting notices on this site. You agree that all Terms and conditions, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing.
                                      </p>
                                      <h6>COPYRIGHT:</h6>
                                      <p class="d-block w-100">
                                        All content included on this site, such as text, graphics, logos, button icons, images, audio clips, digital downloads, data compilations, software, hardware and Equipments is the property of Raj Pay or its content suppliers and protected by international copyright laws. The compilation of all content on this site is the exclusive property of Raj Pay, with copyright authorship for this collection by Raj Pay and protected by international copyright laws.
                                      </p>
                                      <h6>TRADE MARKS:</h6>
                                      <p class="d-block w-100">
                                        Raj Pay trademarks and trade dress may not be used in connection with any product or service that is not A Type, in any manner that is likely to cause confusion among customers, or in any manner that disparages or discredits Raj Pay All other trademarks not owned by Raj Pay that appear on this site are the property of their respective owners, who may or may not be affiliated with, connected to, or sponsored by Raj Pay or its subsidiaries.
                                      </p>
                                      <h6>LICENCE AND SITE ACCESS:</h6>
                                      <p class="d-block w-100">
                                        Raj Pay grants you a limited licence to access and make personal use of this site and not to download or modify it, or any portion of it, except with express written consent of Raj Pay This licence does not include any resale or commercial use of this site or its contents: any collection and use of any product listings, descriptions, or prices: any derivative use of this site or its contents: any downloading or copying of account information for the benefit of another merchants or Customers: or any use of data mining, robots, or similar data gathering and extraction tools and malware or virus or penetration testing. This site or any portion of this site may not be reproduced, duplicated, copied, sold, resold, visited, or otherwise exploited for any commercial purpose without express written consent of Raj Pay You may not frame or utilise framing techniques to enclose any trademark, logo, or other proprietary information (including images, text, page layout, or form) of Raj Pay and our associates without express written consent. You may not use any Meta tags or any other "hidden text" utilising Raj Pay or trademarks without the express written consent of Raj Pay Any unauthorised use terminates the permission or licence granted by Raj Pay You are granted a limited, revocable, and nonexclusive right to create a hyperlink to the homepage of Raj Pay so long as the link does not portray Raj Pay, its associates, or their products or services in a false, misleading, derogatory, or otherwise offensive matter. You may not use any Raj Pay logo or other proprietary graphic or trademark as part of the link without express written permission.
                                      </p>
                                      <h6>REVIEWS, COMMENTS, EMAILS, AND OTHER CONTENT:</h6>
                                      <p class="d-block w-100">
                                        Visitors may post reviews, comments, and other content, and submit suggestions, ideas, comments, questions, or other information, so long as the content is not illegal, obscene, threatening, defamatory, invasive of privacy, infringing of intellectual property rights, or otherwise injurious to third parties or objectionable and does not consist of or contain software viruses, political campaigning, commercial solicitation, chain letters, mass mailings, or any form of "spam." You may not use a false e-mail address, impersonate any person or entity, or otherwise mislead as to the origin of a card or other content. Raj Pay reserves the right (but not the obligation) to remove or edit such content but does not regularly review posted content. If you do post content or submit material, and unless we indicate otherwise, you grant Raj Pay and its associates a nonexclusive, royalty-free, perpetual, irrevocable, and fully sub licensable right to use, reproduce, modify, adapt, publish, translate, create derivative works from, distribute, and display such content throughout the world in any media. You grant Raj Pay and its associates and sub licensees the right to use the name that you submit in connection with such content, if they choose. You represent and warrant that you own or otherwise control all of the rights to the content that you post, that the content is accurate, that use of the content you supply does not violate this policy and will not cause injury to any person or entity, and that you will indemnify Raj Pay or its associates for all claims resulting from content you supply. Raj Pay has the right but not the obligation to monitor and edit or remove any activity or content. Raj Pay takes no responsibility and assumes no liability for any content posted by you or any third party.
                                      </p>
                                      <h6>PRODUCT DESCRIPTIONS:</h6>
                                      <p class="d-block w-100">
                                        Products Division
                                        Technology Services/Product development Trading
                                      </p>
                                      <h6>RESPONSIBILITY FOR YOUR SITE:</h6>
                                      <p class="d-block w-100">
                                        You will be solely responsible for the development, operation, and maintenance of your site and for all materials that appear on your site. For example, you will be solely responsible for: The technical operation of your site and all related equipment. Ensuring the display of Special Links on your site does not violate any Terms and conditions between you and any third party (including without limitation any restrictions or requirements placed on you by a third party that hosts your site). Creating and posting Product descriptions on your site and linking those descriptions to the Raj Pay catalogue.The accuracy and appropriateness of materials posted on your site (including, among other things, all Product-related materials and any information you include within or associate with Special Links) Ensuring that materials posted on your site do not violate or infringe upon the rights of any third party (including, for example, copyrights, trademarks, privacy, or other personal or proprietary rights)Ensuring that materials posted on your site are not libelous or otherwise illegal Ensuring that your site accurately and adequately discloses, either through a privacy policy or otherwise, how you collect, use, store, and disclose data collected from visitors, including, where applicable, that third parties (including advertisers) may serve content and/or advertisements and collect information directly from visitors and may place or recognize cookies on visitors' browsers.We disclaim all liability for these matters. Further, you will indemnify and hold us harmless from all claims, Raj Pay, and expenses (including, without limitation, attorneys' fees) relating to the development, operation, maintenance, and contents of your site. We also advise you to strictly abide by the terms narrated in Clause 1 above of this terms and conditions and any non-observance of the terms therein shall determine our association with immediate effect. 
                                      </p>
                                      <h6>COMPLIANCE WITH LAWS:</h6>
                                      <p class="d-block w-100">
                                        As a condition to your participation in the Program, you agree that while you are a Program participant you will comply with all laws, ordinances, rules, regulations, orders, licenses, permits, judgments, decisions or other requirements of any governmental authority that has jurisdiction over you, whether those laws, etc. are now in effect or later come into effect during the time you are a Program participant. Without limiting the foregoing obligation, you agree that as a condition of your participation in the Program you will comply with all applicable laws of India that govern marketing email and all other anti-spam laws.
                                      </p>
                                      <h6>REFUND POLICY:</h6>
                                      <p class="d-block w-100">
                                        The refund amount in case of any cancellation will be refunded within 72 hours, in case of no show the refund will be processed only after we get refund from the airlines, after deducting the airline cancellation charges and applicable service charge. The amount will be refunded in the form of e-money (limit) in your portal, the refund amount will not be deposited directly in your bank account, debit/credit card.
                                      </p>
                                      <h6>TERM OF THE TERMS AND CONDITIONS:</h6>
                                      <p class="d-block w-100">
                                        The term of this Terms and conditions will begin upon our acceptance of your Program Application and will end when terminated by either party. Either you or we may terminate this Terms and conditions at any time, with or without cause, by giving the other party written notice of termination. Upon the termination of this Terms and conditions for any reason, you will immediately cease use of, and remove from your site, all links to the Raj Pay Site, and all of our trademarks, trade dress, and logos, and all other materials provided by or on behalf of us to you pursuant hereto or in connection with the Program. You are eligible to earn referral fees only on sales of Qualifying Products that occur during the term, and referral fees earned through the date of termination will remain payable only if the related orders are not canceled or returned. We may withhold your final payment for a reasonable time to ensure that the correct amount is paid. Effect of Termination: Upon termination of this Terms and conditions by either side you will cease to be an affiliate and shall remove all links to the Raj Pay site as detailed herein above and non-removal of links and use of all or any of our trademark, trade dress, logos and price are treated as illegal crawling and infringement Raj Pay of our Trademark.
                                      </p>
                                      <h6>MODIFICATION:</h6>
                                      <p class="d-block w-100">
                                        We may modify any of the terms and conditions contained in this Terms and conditions, without prior notice at any time and in our sole discretion, by posting a change Raj Pay notice or a new Terms and conditions on the Raj Pay, Site. Modifications may include, for example, changes Raj Pay in the scope of available referral fees, referral fee schedules, payment procedures, and Program rules. If any modification is unacceptable to you, your only recourse is to terminate this Terms and conditions. Your continued participation in the program following our posting of a change notice or new Terms and conditions on the Raj Pay, site will constitute binding acceptance of the change Raj Pay You and we are independent contractors, and nothing in this Terms and conditions will create any partnership, joint venture, agency, franchise, sales representative, or employment relationship between the parties. You will have no authority to make or accept any offers or representations on our behalf. You will not make any statement, whether on your site or otherwise, that reasonably would contradict anything in this clause.
                                      </p>
                                      <h6>LIMITATION OF LIABILITY:</h6>
                                      <p class="d-block w-100">
                                        We will not be liable for indirect, special, or consequential damages (or any loss of revenue, profits, or data) arising in connection with this Terms and conditions or the Program, even if we have been advised of the possibility of such damages. Further, our aggregate liability arising with respect to this Terms and conditions and the Program will not exceed the total referral fees paid or payable to you under this Terms and conditions
                                      </p>
                                      <h6>INDEPENDENT INVESTIGATION:</h6>
                                      <p class="d-block w-100">
                                        You acknowledge that you have read this Terms and conditions and agree to all its terms and conditions. You understand that we may at any time (directly or indirectly) solicit customer referrals on terms that may differ from those contained in this Terms and conditions or operate web sites that are similar to or compete with your web site. You have independently evaluated the desirability of participating in the program and are not relying on any representation, guarantee, or statement other than as set forth in this Terms and conditions
                                      </p>
                                      <h6>TERMS AND CONDITIONS CHANGE, Raj Pay:</h6>
                                      <p class="d-block w-100">
                                        We may in our discretion change these Terms, Raj Pay Conditions of Use and Privacy Notice, or any aspect of membership, without notice to you. If any change to these terms is found invalid, void, or for any reason unenforceable, that change is severable and does not affect the validity and enforceability of any remaining changes or conditions. YOUR CONTINUED MEMBERSHIP AFTER WE CHANGE THESE TERMS CONSTITUTES YOUR ACCEPTANCE OF THE CHANGES. IF YOU DO NOT AGREE TO ANY CHANGES, YOU MUST CANCEL YOUR ORDER OR SERVICES.
                                      </p>
                                      <h6>APPLICABLE LAW:</h6>
                                      <p class="d-block w-100">
                                        These Terms of Sale shall be governed in accordance with the laws of India without reference to conflict of laws principles. You acknowledge that these Terms of services and Sale are solely for Your benefit. It is not for the benefit of any other person, except for Your successors and permitted assigns.
                                      </p>
                                      <h6>SITE POLICIES, MODIFICATION, AND SEVERABILITY:</h6>
                                      <p class="d-block w-100">
                                        Please review our other policies, such as our “Privacy Policy” posted on this site. These policies also govern your visit to Raj Pay We reserve the right to make changes to our site, policies, and these Conditions of Use at any time. If any of these conditions shall be deemed invalid, void, or for any reason unenforceable, that condition shall be deemed severable and shall not affect the validity and enforceability of any remaining condition.
                                      </p>
                                      <h6>QUESTIONS:</h6>
                                      <p class="d-block w-100">
                                        Questions regarding our Conditions of Usage, Privacy Policy, or other policy related material can be directed to our support staff by clicking on the "Contact Us" link in the side menu. Or you can email us at: Support@pnddigital.in
                                      </p>
                                <h4 id="privacy_policy"><u>PRIVACY & POLICY</u></h4>
                                    <p class="d-block w-100">
                                        THIS PRIVACY POLICY IS AN ELECTRONIC RECORD IN THE FORM OF AN ELECTRONIC CONTRACT FORMED UNDER THE INFORMATION TECHNOLOGY ACT, 2000 AND THE RULES MADE THEREUNDER AND THE AMENDED PROVISIONS PERTAINING TO ELECTRONIC DOCUMENTS / RECORDS IN VARIOUS STATUTES AS AMENDED BY THE INFORMATION TECHNOLOGY ACT, 2000. THIS PRIVACY POLICY DOES NOT REQUIRE ANY PHYSICAL, ELECTRONIC OR DIGITAL SIGNATURE.
                                    </p>
                                    <p class="d-block w-100">
                                        THIS PRIVACY POLICY IS A LEGALLY BINDING DOCUMENT BETWEEN YOU AND “Raj Pay.in.” (BOTH TERMS DEFINED BELOW). THE TERMS OF THIS PRIVACY POLICY WILL BE EFFECTIVE UPON YOUR ACCEPTANCE OF THE SAME (DIRECTLY OR INDIRECTLY IN ELECTRONIC FORM, BY CLICKING ON THE I ACCEPT TAB OR BY USE OF THE WEBSITE OR BY OTHER MEANS) AND WILL GOVERN THE RELATIONSHIP BETWEEN YOU AND “Raj Pay.in.” FOR YOUR USE OF THE WEBSITE (DEFINED BELOW).
                                    </p>
                                    <p class="d-block w-100">
                                        THIS DOCUMENT IS PUBLISHED AND SHALL BE CONSTRUED IN ACCORDANCE WITH THE PROVISIONS OF THE INFORMATION TECHNOLOGY (REASONABLE SECURITY PRACTICES AND PROCEDURES AND SENSITIVE PERSONAL DATA OF INFORMATION) RULES, 2011 UNDER INFORMATION TECHNOLOGY ACT, 2000; THAT REQUIRE PUBLISHING OF THE PRIVACY POLICY FOR COLLECTION, USE, STORAGE AND TRANSFER OF SENSITIVE PERSONAL DATA OR INFORMATION.
                                    </p>
                                    <p class="d-block w-100">
                                        PLEASE READ THIS PRIVACY POLICY CAREFULLY BEFORE YOU CLICK ON “I AGREED”. BY USING THE WEBSITE, YOU INDICATE THAT YOU UNDERSTAND, AGREE AND CONSENT TO THIS PRIVACY POLICY. IF YOU DO NOT AGREE WITH THE TERMS OF THIS PRIVACY POLICY, PLEASE DO NOT USE THIS WEBSITE. YOU HEREBY PROVIDE YOUR UNCONDITIONAL CONSENT OR AGREEMENTS TO “Raj Pay.in.” AS PROVIDED UNDER SECTION 43A AND SECTION 72A OF INFORMATION TECHNOLOGY ACT, 2000.
                                    </p>
                                    <p class="d-block w-100">
                                        By providing us your Information or by making use of the facilities provided by the Website, you hereby consent to the collection, storage, processing and transfer of any or all of Your Personal Information and Non-Personal Information by pnddigital.in. as specified under this Privacy Policy. You further agree that such collection, use, storage and transfer of Your Information shall not cause any loss or wrongful gain to you or any other person.
                                    </p>
                                    <p class="d-block w-100">
                                        The term \"Sensitive Personal Data or Information (SPDI)\" as per Rule 3 of IT Act 2000 & Amendments (2008) shall mean and include: Password (Capable of providing information or access to SPDI listed below) Financial information such as Bank account or Credit Card or Debit Card or other payment instrument details Sexual Orientation Any of the detail relating to the above categories of SPDI or information received under above categories of SPDI by the organisation for processing,stored or processed under lawful contract or otherwise The Company is committed to safeguard the privacy of all our website users. This privacy policy sets out how we will treat your personal information.
                                    </p>

                                    <h6>Raj Pay.in HAS PROVIDED THIS PRIVACY POLICY TO YOU WITH:</h6>
                                    <p class="d-block w-100">
                                        The type of data or information that You share with Us or provide to Raj Pay.in. And that Raj Pay.in. collects from You.
                                    </p>

                                    <h6>The purpose for collection of such data or information from You:</h6>
                                    <p class="d-block w-100">
                                        pnddigital.in. information security practices and policies; and Raj Pay.in. Policy on sharing or transferring Your data or information with third parties. This Privacy Policy may be amended / updated from time to time. Upon amending / updating the Privacy Policy, we will accordingly amend the date above. We suggest that you regularly check this Privacy Policy to apprise yourself of any updates. Your continued use of the Website or provision of data or information thereafter will imply Your unconditional acceptance of such updates to this Privacy Policy.
                                    </p>

                                    <h6>PERSONAL IDENTIFIABLE INFORMATION:</h6>
                                    <p class="d-block w-100">
                                        When you use our Website, we collect and store your personal information which is provided by you from time to time. Our primary goal in doing so is to provide you a safe, efficient, smooth and customized experience. This allows us to provide services and features that most likely meet your needs, and to customize our Website to make your experience safer and easier. More importantly, while doing so we collect personal information from you that we consider necessary for achieving this purpose.

                                        In general, you can browse the Website without telling us who you are or revealing any personal information about yourself. Once you give us your personal information, you are not anonymous to us. Where possible, we indicate which fields are required and which fields are optional. You always have the option to not provide information by choosing not to use a particular service or feature on the Website. We may automatically track certain information about you based upon your behaviour on our Website. We use this information to do internal research on our users' demographics, interests, and behaviour to better understand, protect and serve our users. This information is compiled and analysed on an aggregated basis. This information may include the URL that you just came from (whether this URL is on our Website or not), which URL you next go to (whether this URL is on our Website or not), your computer browser information, and your IP address.

                                        We use data collection devices such as \"cookies\" on certain pages of the Website to help analyse our web page flow, measure promotional effectiveness, and promote trust and safety. \"Cookies\" are small files placed on your hard drive that assist us in providing our services. We offer certain features that are only available through the use of a \"cookie\". We also use cookies to allow you to enter your password less frequently during a session. Cookies can also help us provide information that is targeted to your interests. Most cookies are \"session cookies,\" meaning that they are automatically deleted from your hard drive at the end of a session. You are always free to decline our cookies if your browser permits, although in that case you may not be able to use certain features on the Website and you may be required to re-enter your password more frequently during a session.

                                        Additionally, you may encounter \"cookies\" or other similar devices on certain pages of the Website that are placed by third parties. We do not control the use of cookies by third parties.

                                        If you transact with us, we collect some additional information, such as a billing address, a credit / debit card number and a credit / debit card expiration date and/ or other payment instrument details and tracking information from cheques or money orders

                                        If you choose to post messages on our message boards, chat rooms or other message areas or leave feedback, we will collect that information you provide to us. We retain this information as necessary to resolve disputes, provide customer support and troubleshoot problems as permitted by law.

                                        If you send us personal correspondence, such as emails or letters, or if other users or third parties send us correspondence about your activities or postings on the Website, we may collect such information into a file specific to you.

                                        We collect personally identifiable information (email address, name, phone number, credit card / debit card / other payment instrument details, etc.) from you when you set up a free account with us. While you can browse some sections of our Website without being a registered member, certain activities (such as placing an order) do require registration. We do use your contact information to send you offers based on your previous orders and your interests.
                                    </p>

                                    <h6>INFORMATION SECURITY STANDARDS:</h6>
                                    <p class="d-block w-100">
                                        We assured you that we follow ISO/IEC 27001:2013 standard to protect your personal information and try to get continual improvement.
                                    </p>
                                    <h6>USE OF YOUR INFORMATION:</h6>
                                    <p class="d-block w-100">
                                        We use personal information to provide the services you request. To the extent we use your personal information to market to you, we will provide you the ability to opt-out of such uses. We use your personal information to resolve disputes; troubleshoot problems; help promote a safe service; collect money; measure consumer interest in our products and services, inform you about online and offline offers, products, services, and updates; customize your experience; detect and protect us against error, fraud and other criminal activity; enforce our terms and conditions; and as otherwise described to you at the time of collection.
                                    </p>
                                    <h6>COOKIES:</h6>
                                    <p class="d-block w-100">
                                        If you don’t want to allow cookies at all, or only want to allow use of certain cookies, please refer to your browser settings. You can also use your browser settings to withdraw your consent to our use of cookies at any time and delete cookies that have already been set. Your browser help menu or the website www.allaboutcookies.org contains comprehensive information about the process of opting out on different browsers.

                                        Note that by disabling certain categories of cookies, you may be prevented from accessing some features of our sites or certain content or functionality may not be available.
                                    </p>
                                    <h6>SHARING OF PERSONAL INFORMATION:</h6>
                                    <p class="d-block w-100">
                                        We may share personal information with our other corporate entities and affiliates. These entities and affiliates may market to you as a result of such sharing unless you explicitly opt-out.

                                        We may disclose personal information to third parties. This disclosure may be required for us to provide you access to our Services, to comply with our legal obligations, to enforce our User Agreement, to facilitate our marketing and advertising activities, or to prevent, detect, mitigate, and investigate fraudulent or illegal activities related to our Services. We do not disclose your personal information to third parties for their marketing and advertising purposes without your explicit consent.

                                        We may disclose personal information if required to do so by law or in the good faith belief that such disclosure is reasonably necessary to respond to subpoenas, court orders, or other legal process. We may disclose personal information to law enforcement offices, third party rights owners, or others in the good faith belief that such disclosure is reasonably necessary to: enforce our Terms or Privacy Policy; respond to claims that an advertisement, posting or other content violates the rights of a third party; or protect the rights, property or personal safety of our users or the general public

                                        We and our affiliates will share / sell some or all of your personal information with another business entity should we (or our assets) plan to merge with, or be acquired by that business entity, or reorganisation, amalgamation, restructuring of business. Should such a transaction occur, another business entity (or the new combined entity) will be required to follow this privacy policy with respect to your personal information.
                                    </p>

                                    <h6>LINKS TO OTHER SITES:</h6>
                                    <p class="d-block w-100">
                                        Our Website links to other websites that may collect personally identifiable information about you. Raj Pay.in. is not responsible for the privacy practices or the content of those linked websites.
                                    </p>
                                    <h6>SECURITY PRECAUTIONS:</h6>
                                    <p class="d-block w-100">
                                        Our Website has stringent security measures in place to protect the loss, misuse, and alteration of the information under our control. Whenever you change or access your account information, we offer the use of a secure server. Once your information is in our possession we adhere to strict security guidelines, protecting it against unauthorised access.
                                    </p>
                                    <h6>CHOICE/OPT-OUT:</h6>
                                    <p class="d-block w-100">
                                        We provide all users with the opportunity to opt-out of receiving non-essential (promotional, marketing-related) communications from us on behalf of our partners, and from us in general, after setting up an account.
                                        If you want to remove your contact information from all Raj Pay.in. lists and newsletters, please visit unsubscribe\nYOUR CONSENT

                                        By using the Website and/ or by providing your information, you consent to the collection and use of the information you disclose on the Website in accordance with this Privacy Policy, including but not limited to Your consent for sharing your information as per this privacy policy.

                                        If we decide to change our privacy policy, we will post those changes on this page so that you are always aware of what information we collect, how we use it, and under what circumstances we disclose it.
                                    </p>
                                    <h6>CONTACT US:</h6>
                                    <p class="d-block w-100">
                                        We value your feedback. If you have any questions or concerns about our Privacy policy, our collection and use of your data or a possible breach of local privacy laws, you can contact us via email to the Raj Pay.in.

                                        All communications will be treated confidentially. Upon receipt of your communication, our representative will contact you within a reasonable time to respond to your questions or concerns. We aim to ensure that your concerns are resolved in a timely and appropriate manner

                                    </p>
                                    <h6>PRIVACY POLICY CHANGES:</h6>
                                    <p class="d-block w-100">
                                        If we modify our Privacy Policy, we will post the revised statement here, with an updated revision date. If we make significant changes to our Privacy Policy that materially alter our privacy practices, we may also notify you by other means, such as sending an email or posting a notice on our corporate website and/or social media pages prior to the changes taking effect.
                                    </p>
             
                                    <div class="container">
                                        <div class="copyright-inner pt-10 bg-dark px-3 " style="text-align: center; border-radius: 2px">
                                            <p class="text-white">Copyright 2023 Restly All Rights Reserved.</p>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>