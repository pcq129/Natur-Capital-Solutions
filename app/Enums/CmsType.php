<?php

namespace App\Enums;

enum CmsType: string
{
    case PRIVACY_POLICY = 'privacy-policy';
    case TERMS_OF_SERVICE = 'terms-of-service';
    case COOKIE_POLICY = 'cookie-policy';
    case DISCLAIMER = 'disclaimer';
    case ABOUT_US = 'about-us';
    case CONTACT_US = 'contact-us';
    case ACCESSIBILITY = 'accessibility';
    case FAQ = 'faq';
    case SUPPORT = 'support';
    case LEGAL_NOTICE = 'legal-notice';
    case SITEMAP = 'sitemap';

    // public function label(): string
    // {
    //     return match ($this) {
    //         self::PRIVACY_POLICY => 'Privacy Policy',
    //         self::TERMS_OF_SERVICE => 'Terms of Service',
    //         self::COOKIE_POLICY => 'Cookie Policy',
    //         self::DISCLAIMER => 'Disclaimer',
    //         self::ABOUT_US => 'About Us',
    //         self::CONTACT_US => 'Contact Us',
    //         self::ACCESSIBILITY => 'Accessibility',
    //         self::FAQ => 'FAQ',
    //         self::SUPPORT => 'Support',
    //         self::LEGAL_NOTICE => 'Legal Notice',
    //         self::SITEMAP => 'Sitemap',
    //     };
    // }
}
