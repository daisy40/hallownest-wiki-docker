<?php
/**
 * MediaWiki configuration setting for Hallownest Wiki
 *
 * MediaWiki branch: REL1_46
 * When updating major MediaWiki version, please update the branch text above
 * in this document, it will update the documentation links to the right version.
 *
 * @see https://www.mediawiki.org/wiki/Manual:LocalSettings.php Documentation
 * @link https://hallownest.wiki/ Offical site
 */

// Protect against web entry
if (!defined("MEDIAWIKI")) {
    exit();
}

const HW_DOMAIN = "hallownest.wiki";

/**
 * Maintenance config
 */
// $wgReadOnly = 'Maintenance is underway. Website is on read-only mode';

/**
 * Debug/Development config
 * Do not enable these on production unless necessary
 *
 * @see https://www.mediawiki.org/wiki/Manual:How_to_debug
 */
// error_reporting( -1 );
// ini_set( 'display_errors', 1 );
// $wgShowExceptionDetails = true;
// $wgDebugToolbar = true;
// $wgDevelopmentWarnings = true;
// $wgDebugDumpSql = true;
// $wgDebugComments = true;

/**
 * MediaWiki core main config
 *
 * @see https://github.com/wikimedia/mediawiki/blob/REL1_46/includes/MainConfigSchema.php Definitions
 * @see https://www.mediawiki.org/wiki/Manual:Configuration_settings Documentation
 */

/**
 * Keys
 */
$wgSecretKey = getenv("MEDIAWIKI_SECRETKEY");
$wgUpgradeKey = getenv("MEDIAWIKI_UPGRADEKEY");

/**
 * Server/site settings
 */
$wgSitename = "Hollow Knight Вики";
$wgServer = "https://" . HW_DOMAIN;
// Short URL paths
$wgArticlePath = '/$1';
$wgScriptPath = "";
// Main page is served as the domain root
$wgMainPageIsDomainRoot = true;
$wgLanguageCode = "ru";
$wgLocaltimezone = "UTC";
$wgMaxShellMemory = 0;

// Copyright
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "https://creativecommons.org/licenses/by-sa/4.0/";
$wgRightsText = "Creative Commons Attribution-ShareAlike";
$wgRightsIcon = "$wgResourceBasePath/resources/assets/licenses/cc-by-sa.png";

// Logos and icons
$wgLogos = [
    "svg" => "$wgResourceBasePath/resources/assets/sitelogo.png",
];
$wgFavicon = "$wgResourceBasePath/resources/assets/favicon.ico";

/**
 * Database settings
 */
$wgDBserver = "db";
$wgDBname = getenv("DB_NAME");
$wgDBuser = getenv("DB_USER");
$wgDBpassword = getenv("DB_PASSWORD");
$wgDBprefix = "";
// MySQL table options to use during installation or update
$wgDBTableOptions = "ENGINE=InnoDB, DEFAULT CHARSET=binary";

/**
 * Cache settings
 */
// Set $wgCacheDirectory to a writable directory on the web server
// to make your wiki go slightly faster. The directory should not
// be publically accessible from the web.
$wgCacheDirectory = "$IP/cache";

// Define redis
$wgObjectCaches["valkey"] = [
    "class" => "RedisBagOStuff",
    "servers" => ["valkey:6379"],
    "persistent" => true,
];

// https://phabricator.wikimedia.org/T352481
$wgMainStash = "valkey";
$wgMainCacheType = "valkey";
$wgParserCacheType = "valkey";
$wgSessionCacheType = "valkey";
$wgLanguageConverterCacheType = "valkey";
$wgEnableSidebarCache = true;
$wgUseLocalMessageCache = true;
$wgGitInfoCacheDirectory = "$IP/cache/gitinfo";

// Extend parser cache to 3 days
$wgParserCacheExpireTime = 259200;

// Extend search suggestions response cache to 1 days
// This allows the CDN to cache the search suggestions response
$wgSearchSuggestCacheExpiry = 86400;

// Cloudflare CDN settings
$wgUseCdn = true;
$wgCdnMaxAge = $wgParserCacheExpireTime;
$wgCdnMatchParameterOrder = false;
$wgCdnServersNoPurge = [
    // Internal IPs
    "172.16.0.0/12",
    // Cloudflare IPv4
    // https://www.cloudflare.com/ips
    "173.245.48.0/20",
    "103.21.244.0/22",
    "103.22.200.0/22",
    "103.31.4.0/22",
    "141.101.64.0/18",
    "108.162.192.0/18",
    "190.93.240.0/20",
    "188.114.96.0/20",
    "197.234.240.0/22",
    "198.41.128.0/17",
    "162.158.0.0/15",
    "104.16.0.0/13",
    "104.24.0.0/14",
    "172.64.0.0/13",
    "131.0.72.0/22",
    // Cloudflare IPv6
    "2400:cb00::/32",
    "2606:4700::/32",
    "2803:f800::/32",
    "2405:b500::/32",
    "2405:8100::/32",
    "2a06:98c0::/29",
    "2c0f:f248::/32",
];
$wgUsePrivateIPs = true;

/**
 * Security and privacy settings
 */
$wgForceHTTPS = true;
// Set X-Frame-Options to DENY
$wgBreakFrames = true;
$wgCSPHeader = [
    "script-src" => [
        '\'self\'',
    ],
    "default-src" => [
        '\'self\'',
    ],
    "style-src" => ['\'self\''],
    "object-src" => ['\'none\''],
];
// $wgReferrerPolicy = 'strict-origin-when-cross-origin'; // Enforced through HTTP header
$wgCookieSameSite = "Strict";
// Only send cookies over HTTPS
$wgCookieSecure = true;
// Use argon2 to hash user password (MW default: 'pbkdf2')
$wgPasswordDefault = "argon2";
// Remove the real name field from sign up and preference page
$wgHiddenPrefs[] = "realname";
// Open external link in new tab/window
$wgExternalLinkTarget = "_blank";

/**
 * Performance settings
 */
$wgMultiShardSiteStats = true;
// Don't invalidate cache for changes in localsettings.php,
// instead use $wgCacheEpoch above to do it.
//$wgInvalidateCacheOnLocalSettingsChange = false;
// Job queue
$wgJobTypeConf["default"] = [
    "class"       => "JobQueueRedis",
    "redisServer" => "valkey-jobs:6379",
    "redisConfig" => [],
    "claimTTL"    => 3600,
    "checkDelay"  => true,
    "daemonized"  => true,
];
// We have jobrunner set up so don't run any jobs on request
$wgJobRunRate = 0;
// Avoid excessive CPU due to cache misses from rapid invalidations
$wgJobBackoffThrottling["htmlCacheUpdate"] = 50;

/**
 * Output settings
 */
// Use HTML5 encoding with minimal escaping
$wgFragmentMode = ["html5"];
// Enable sorted categories (T373480)
$wgSortedCategories = true;
// Allow MediaWiki:Citizen.css to load on all pages
$wgAllowSiteCSSOnRestrictedPages = true;
// Output a canonical meta tag on every page
$wgEnableCanonicalServerLink = true;
// Enable native lazyloading
$wgNativeImageLazyLoading = true;

/**
 * File settings
 */
// Enable image uploads
// Make sure the 'images' directory is writable
$wgEnableUploads = true;
$wgIgnoreImageErrors = true;
$wgMaxImageArea = 6.4e7;
$wgUploadPath = "https://media.hallownest.wiki/images";
//$wgUseImageMagick = true;
// Stream and serve thumbnails with thumb.php
// $wgGenerateThumbnailOnParse = false;
// $wgThumbnailScriptPath = "$wgScriptPath/thumb.php";

// SVG Support
$wgFileExtensions[] = "svg";
$wgAllowTitlesInSVG = true;

/**
 * Standardize thumbnail sizes
 * MediaWiki thumbnailing is all over the place (T360589)
 *
 * TODO: Wait on https://gerrit.wikimedia.org/r/c/mediawiki/core/+/1084920
 *
 * List of image widths on the wiki
 * 120px - File history on file page (ImageHistoryList.php)
 * 160px - Thumb size 0
 * 320px - Thumb size 1 / Image size 0
 * 480px - Responsive image
 * 640px - Image size 1
 * 1280px - Image size 2
 * 1920px - Responsive image
 * 2560px - Image size 3
 */

/** @var array Standardized thumb sizes (Multiples of 80) */
const HW_THUMB_SIZES = [
    [160, 120],
    [240, 180],
    [320, 240],
    [640, 480],
    [1280, 1024],
    [2560, 2048],
];

// Reduce the number of thumb sizes served
$wgThumbLimits = [
    HW_THUMB_SIZES[0][0], // thumb size 0
    HW_THUMB_SIZES[1][0], // thumb size 1
    HW_THUMB_SIZES[2][0], // thumb size 2
];
// Set to 300px thumb by default
$wgDefaultUserOptions["thumbsize"] = 2;
// Reduce the number of image sizes served in description page
$wgImageLimits = [
    [HW_THUMB_SIZES[2][0], HW_THUMB_SIZES[2][1]], // image size 0
    [HW_THUMB_SIZES[3][0], HW_THUMB_SIZES[3][1]], // image size 1
    [HW_THUMB_SIZES[4][0], HW_THUMB_SIZES[4][1]], // image size 2
    [HW_THUMB_SIZES[5][0], HW_THUMB_SIZES[5][1]], // image size 3
];
// Set to 1280px image by default
$wgDefaultUserOptions["imagesize"] = 2; // image size 2

// Use intermediary thumbnails to speed up thumbnail rendering
// This will result in several chained lossy transformations
// but we need it because the wiki uses a lot of high quality images
$wgThumbnailBuckets = [HW_THUMB_SIZES[4][0]];
$wgThumbnailMinimumBucketDistance = 100;

// Gallery settings
// Sync with default image size 0
$wgGalleryOptions["imageWidth"] = $wgImageLimits[0][0];
$wgGalleryOptions["imageHeight"] = $wgImageLimits[0][1];
$wgGalleryOptions['mode'] = 'packed';

/**
 * Content settings
 */
// Fix double redirects after a page move
$wgFixDoubleRedirects = true;
// Allow pages to override their title
$wgRestrictDisplayTitle = false;

/**
 * User settings
 */
// Allow user styles and scripts
$wgAllowUserCss = true;
$wgAllowUserJs = true;
// Allow logged-in users to set a preference whether or not matches
// in search results should force redirection to that page.
$wgSearchMatchRedirectPreference = true;
$wgEnableEditRecovery = true;

/**
 * Namespace settings
 *
 * @see https://www.mediawiki.org/wiki/Manual:Using_custom_namespaces#Creating_a_custom_namespace
 */
// Default to $wgSitename but we need to escape it with underscores
$wgMetaNamespace = "Hollow_Knight_Вики";
// Add permission to edit templates
$wgNamespaceProtection[NS_TEMPLATE] = ["template-edit"];

// Namespace alias
// Use capital case to avoid conflicts with interwiki links
$wgNamespaceAliases = [
    "К" => NS_CATEGORY,
    "C" => NS_CATEGORY,
    "Ф" => NS_FILE,
    "F" => NS_FILE,
    "П" => NS_HELP,
    "H" => NS_HELP,
    "HW" => NS_PROJECT,
    "Ш" => NS_TEMPLATE,
    "T" => NS_TEMPLATE,
];

$wgContentNamespaces = [
    NS_MAIN,
];

$wgVisualEditorAvailableNamespaces = [
    NS_MAIN => true,
    NS_USER => true,
    NS_HELP => true,
    NS_PROJECT => true,
];

// Sitemap
$wgSitemapNamespaces = array_merge($wgContentNamespaces, [
    NS_FILE,
    NS_CATEGORY,
]);

/**
 * Extensions and skins settings
 */
wfLoadExtensions([
    "AbuseFilter",
    "AdvancedSearch",
    "AGGrid",
    "AntiSpoof",
    "Apiunto",
    "Cargo",
    "CategorySortHeaders",
    "CategoryTree",
    "CheckUser",
    "CirrusSearch",
    "Cite",
    "Cldr",
    "CodeEditor",
    "CodeMirror",
    "CommonsMetadata",
    "ConfirmEdit",
    "ConfirmEdit/QuestyCaptcha",
    "Details",
    "Disambiguator",
    "DiscussionTools",
    "DismissableSiteNotice",
    "Echo",
    "Elastica",
    "EmbedVideo",
    "FloatingUI",
    "Gadgets",
    "HoneyPot",
    "Linter",
    "LoginNotify",
    "Math",
    "MediaSearch",
    "MsUpload",
    "MultimediaViewer",
    "MultiPurge",
    "Nuke",
    "PageImages",
    "ParserFunctions",
    "PictoCat",
    "Poem",
    "Popups",
    "RelatedArticles",
    "ReplaceText",
    "RevisionSlider",
    "SandboxLink",
    "Scribunto",
    "SearchDigest",
    "SearchThumbs",
    "SecureLinkFixer",
    "ShortDescription",
    "SyntaxHighlight_GeSHi",
    "TabberNeue",
    "TableProgressTracking",
    "TemplateData",
    "TemplateSandbox",
    "TemplateStyles",
    "TemplateStylesExtender",
    "TextExtracts",
    "Thanks",
    "Thumbro",
    "TwoColConflict",
    "UploadWizard",
    "UrlShortener",
    "UserProfileV2",
    "Variables",
    "VisualEditor",
    "WikiEditor",
    "WikiSEO",
    "Yappin",
]);

// Citizen needs to be loaded after extensions to display correct icons for extensions
wfLoadSkin("Citizen");
// Set Citizen to the default skin
$wgDefaultSkin = "citizen";

/**
 * Extension:AbuseFilter
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-AbuseFilter
 */
// Syntax highlight the filter editor on Special:AbuseFilter. Without this the
// editor falls back to a plain textarea now that CodeEditor is gone
$wgAbuseFilterUseCodeMirror = true;

/**
 * Extension:AdvancedSearch
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-AdvancedSearch
 */
// We don't have the service to support deepcat:
// @see https://gerrit.wikimedia.org/g/mediawiki/extensions/AdvancedSearch/+/3019b4f8c10139b85737372b6c6f47981e2d0796/docs/settings.md#category-tree-support
$wgAdvancedSearchDeepcatEnabled = false;

/**
 * Extension:Apiunto
 *
 * @see https://github.com/StarCitizenTools/mediawiki-extensions-Apiunto
 */
$wgApiuntoSources = [
    "SteamAPI" => [
        "baseUrl"       => "https://api.steampowered.com/",
        "token"         => "",
        "timeout"       => 30,
        "cacheDuration" => 86400,
    ]
];

/**
 * Extension:CheckUser
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-CheckUser
 */
// Always give a comment on why checkuser is used
$wgCheckUserForceSummary = true;

/**
 * Extension:CirrusSearch
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-CirrusSearch
 */
$wgSearchType = "CirrusSearch";
$wgCirrusSearchIndexBaseName = "hw_prod";
$wgCirrusSearchServers = [
    [
        "host"   => "opensearch",
        "port"   => 9200,
        "scheme" => "http",
    ],
];
$wgCirrusSearchUseIcuFolding = true;
$wgCirrusSearchUseCompletionSuggester = "yes";
$wgCirrusSearchCompletionSettings = "fuzzy-subphrases";
$wgCirrusSearchCompletionSuggesterSubphrases = [
    "build" => true,
    "use"   => true,
    "type"  => "anywords",
    "limit" => 10,
];

/**
 * Extension:CodeMirror
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-CodeMirror
 */
// Enable syntax highlight in editor by default
$wgDefaultUserOptions["usecodemirror"] = 1;
// CodeMirror only opts into wikitext out of the box, so enable the content
// models that Extension:CodeEditor used to handle
$wgCodeMirrorEnabledModes["javascript"] = true;
$wgCodeMirrorEnabledModes["json"] = true;
$wgCodeMirrorEnabledModes["css"] = true;
$wgCodeMirrorEnabledModes["lua"] = true;
$wgCodeMirrorEnabledModes["vue"] = true;

/**
 * Extension:CommonsMetadata
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-CommonsMetadata
 */
// Enable tracking categories for CommonsMetadata, to track files without proper metadata
$wgCommonsMetadataSetTrackingCategories = true;

/**
 * Extension:ConfirmEdit
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-ConfirmEdit
 */
$wgCaptchaClass = "QuestyCaptcha";
$wgCaptchaTriggers["edit"] = true;
$wgCaptchaTriggers["create"] = true;
// Questy Catpcha
$wgCaptchaQuestions = [
    "Какое название компании, разработавшей Hollow Knight?" => [
        "team cherry",
        "teamcherry",
    ],
];

/**
 * Extension:Details
 *
 * @see https://github.com/chariz/mediawiki-extensions-Details
 */
// Disable custom handling since we only need to write <details> and <summary> in wikitext
$wgDetailsMWCollapsibleCompatibility = false;

/**
 * Extension:Disambiguator
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-Disambiguator
 */
$wgDisambiguatorNotifications = true;

/**
 * Extension:DiscussionTools
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-DiscussionTools
 */
$wgDiscussionTools_visualenhancements = "available";

/**
 * Extension:DismissableSiteNotice
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-DismissableSiteNotice
 */
// Allow anon to dismiss site notice
$wgDismissableSiteNoticeForAnons = true;

/**
 * Extension:Echo
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-Echo
 */
$wgEchoUseJobQueue = true;

/**
 * Extension:EmbedVideo
 *
 * @see https://github.com/StarCitizenWiki/mediawiki-extensions-EmbedVideo
 */
// Disable the embed styles so that the EmbedVideo ResourceLoader modules
// won't load on every single page
$wgEmbedVideoUseEmbedStyleForLocalVideos = false;

/**
 * Extension:HoneyPot
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-HoneyPot
 */
$wgHoneyPotMisleadingError = true;

/**
 * Extension:MultimediaViewer
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-MultimediaViewer
 */
$wgMediaViewerThumbnailBucketSizes = [
    HW_THUMB_SIZES[2][0],
    HW_THUMB_SIZES[3][0],
    HW_THUMB_SIZES[4][0],
    HW_THUMB_SIZES[5][0],
];

/**
 * Extension:MultiPurge
 *
 * @see https://github.com/octfx/mediawiki-extensions-MultiPurge
 */
$wgMultiPurgeEnabledServices = ["Cloudflare"];
$wgMultiPurgeServiceOrder = $wgMultiPurgeEnabledServices;
$wgMultiPurgeCloudFlareZoneId = getenv("CLOUDFLARE_ZONEID");
$wgMultiPurgeCloudFlareApiToken = getenv("CLOUDFLARE_APITOKEN");
$wgMultiPurgeStaticPurges = [
    "Startup script" =>
        "load.php?lang=en&modules=startup&only=scripts&raw=1&skin=citizen",
    "Site styles" =>
        "load.php?lang=en&modules=site.styles&only=styles&skin=citizen",
];
// Keep the render refreshLinks makes after a template or module edit instead
// of letting core discard it, so those pages are not parsed a second time
$wgMultiPurgeWarmParserCacheOnRefreshLinks = true;

/**
 * Extension:PageImages
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-PageImages
 */
$wgPageImagesAPIDefaultLicense = "any";
$wgPageImagesLeadSectionOnly = false;
$wgPageImagesNamespaces = $wgContentNamespaces;

/**
 * Parsoid
 * Need to load Parsoid explicitly to make Extension:Linter work
 *
 * @see https://github.com/StarCitizenWiki/WikiDocker/commit/ea149d74daba5cc13594cee57db70dab099e214d
 */
wfLoadExtension("Parsoid", "$IP/vendor/wikimedia/parsoid/extension.json");
$wgParsoidSettings = [
    "useSelser" => true,
    "linting" => true,
];

/**
 * Extension:RelatedArticles
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-RelatedArticles
 */
// Enable RelatedArticle for Citizen
$wgRelatedArticlesFooterWhitelistedSkins = ["citizen"];
// Needed because we changed script path for Short URL
$wgRelatedArticlesUseCirrusSearchApiUrl = "/api.php";
// wikidata is supplied by Extension:ShortDescription
$wgRelatedArticlesDescriptionSource = "wikidata";
$wgRelatedArticlesUseCirrusSearch = true;
$wgRelatedArticlesOnlyUseCirrusSearch = true;
// 5 is weird since we have a 3 col layout
$wgRelatedArticlesCardLimit = 6;

/**
 * Extension:Scribunto
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-Scribunto
 */
$wgScribuntoDefaultEngine = "luasandbox";
$wgScribuntoEngineConf["luasandbox"]["memoryLimit"] = 50 * 1024 * 1024; // 50 MB
$wgScribuntoEngineConf["luasandbox"]["cpuLimit"] = 10; // Seconds
$wgScribuntoGatherFunctionStats = true;

/**
 * Extension:SyntaxHighlight
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-SyntaxHighlight_GeSHi
 */
// Use more updated Pygmentize from system instead of bundled one
// $wgPygmentizePath = "/usr/local/bin/pygmentize";

/**
 * Extension:TemplateSandbox
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-TemplateSandbox
 */
// Add module namespace support for TemplateSandbox
//$wgTemplateSandboxEditNamespaces[] = NS_MODULE;

/**
 * Extension:TemplateStyles
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-TemplateStyles
 */
$wgTemplateStylesAllowedUrls = [
    "audio" => [
        "<^https://hallownest\\.wiki/>",
        "<^https://media\\.hallownest\\.wiki/>",
    ],
    "image" => [
        "<^https://hallownest\\.wiki/>",
        "<^https://media\\.hallownest\\.wiki/>",
    ],
    "svg" => [
        '<^https://hallownest\\.wiki/[^?#]*\\.svg(?:[?#]|$)>',
        '<^https://media\\.hallownest\\.wiki/[^?#]*\\.svg(?:[?#]|$)>',
    ],
    "font" => ["<^https://hallownest\\.wiki/>"],
    "namespace" => ["<.>"],
    "css" => [],
];
$wgTemplateStylesNamespaces = [
    NS_TEMPLATE => true,
    //NS_MODULE => true,
];
$wgTemplateStylesUseCodeMirror = true;

/**
 * Extension:TemplateStylesExtender
 *
 * @see https://github.com/octfx/mediawiki-extensions-TemplateStylesExtender
 */
// We have CSP and this allows us to set image URL as CSS variables in Lua modules
$wgTemplateStylesExtenderAllowExternalResourcesInCustomProperties = true;

/**
 * Extension:TextExtracts
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-TextExtracts
 */
$wgExtractsRemoveClasses[] = ".metadata";

/**
 * Extension:Thumbro
 *
 * @see https://github.com/StarCitizenTools/mediawiki-extensions-Thumbro
 */
// We restricted the page to specific user groups
// Check the group permission settings below
// $wgThumbroExposeTestPage = true;

/**
 * Extension:TwoColConflict
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-TwoColConflict
 */
$wgTwoColConflictBetaFeature = false;

/**
 * Extension:UploadWizard
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-UploadWizard
 */
// MW core settings that are required by UploadWizard
$wgApiFrameOptions = "SAMEORIGIN";
$wgUploadNavigationUrl = "/Special:UploadWizard";

// Extension settings
// @see https://github.com/wikimedia/mediawiki-extensions-UploadWizard/blob/REL1_43/UploadWizard.config.php
$wgUploadWizardConfig = [
    "campaignExpensiveStatsEnabled" => false,
    "tutorial" => [
        "skip" => true,
    ],
    // Custom licenses
    "licenses" => [
        // Team Cherry license
        "tclicense" => [
            // HACK: Add custom license message
            // Edit MediaWiki:mwe-upwiz-license-pd-usgov to the text you wanted
            "msg" => "mwe-upwiz-license-pd-usgov",
            // 'msg' => 'mwe-upwiz-license-fs',
            "templates" => ["Лицензия Team Cherry"],
        ],
    ],
    // License selection page
    "licensing" => [
        "ownWork" => [
            "type" => "or",
            "template" => "self",
            "defaults" => ["cc-by-sa-4.0"],
            "licenses" => ["cc-by-sa-4.0", "cc-by-4.0", "cc-zero"],
        ],
        "thirdParty" => [
            "type" => "or",
            "defaults" => "fslicense",
            "licenseGroups" => [
                [
                    // Team Cherry license
                    // HACK: Add custom license header
                    // Edit MediaWiki:mwe-upwiz-license-usgov-head to the text you wanted
                    // We have to use this because this message is loaded by UploadWizard and we don't use it
                    "head" => "mwe-upwiz-license-usgov-head",
                    // 'head' => 'mwe-upwiz-license-sc-head',
                    "defaults" => ["tclicense"],
                    "licenses" => ["tclicense"],
                ],
                [
                    // This should be a list of all CC licenses we can reasonably expect to find around the web
                    "head" => "mwe-upwiz-license-cc-head",
                    "subhead" => "mwe-upwiz-license-cc-subhead",
                    "defaults" => ["cc-by-sa-4.0"],
                    "licenses" => ["cc-by-sa-4.0", "cc-by-4.0", "cc-zero"],
                ],
                [
                    "head" => "mwe-upwiz-license-custom-head",
                    "special" => "custom",
                    "defaults" => ["custom"],
                    "licenses" => ["custom"],
                ],
                [
                    "head" => "mwe-upwiz-license-none-head",
                    "defaults" => ["none"],
                    "licenses" => ["none"],
                ],
            ],
        ],
    ],
    "maxUploads" => 20,
    "allCategoriesLink" => "",
    "altUploadForm" => "Special:Upload",
    "alternativeUploadToolsPage" => "",
];

/**
 * Extension:UrlShortener
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-UrlShortener
 */
$wgUrlShortenerEnableQrCode = true;
$wgUrlShortenerTemplate = '/r/$1';

/**
 * Extension:UserProfileV2
 *
 * @see https://gitlab.com/telepedia/extensions/userprofilev2
 */
$wgUserProfileV2ProfileFields = [
    'profile-aboutme',
    [
        'key' => 'profile-github',
        'type' => 'text',
        'label' => 'GitHub',
        'name' => 'GitHub',
        'placeholder' => 'Никнейм',
        'help' => 'Ваш никнейм на GitHub',
        'url' => 'https://github.com/$1',
        'maxlength' => 64,
        'icon' => '<?xml version="1.0" encoding="UTF-8"?><svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000"><path d="M16 22.0268V19.1568C16.0375 18.68 15.9731 18.2006 15.811 17.7506C15.6489 17.3006 15.3929 16.8902 15.06 16.5468C18.2 16.1968 21.5 15.0068 21.5 9.54679C21.4997 8.15062 20.9627 6.80799 20 5.79679C20.4558 4.5753 20.4236 3.22514 19.91 2.02679C19.91 2.02679 18.73 1.67679 16 3.50679C13.708 2.88561 11.292 2.88561 8.99999 3.50679C6.26999 1.67679 5.08999 2.02679 5.08999 2.02679C4.57636 3.22514 4.54413 4.5753 4.99999 5.79679C4.03011 6.81549 3.49251 8.17026 3.49999 9.57679C3.49999 14.9968 6.79998 16.1868 9.93998 16.5768C9.61098 16.9168 9.35725 17.3222 9.19529 17.7667C9.03334 18.2112 8.96679 18.6849 8.99999 19.1568V22.0268" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M9 20.0267C6 20.9999 3.5 20.0267 2 17.0267" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
    ],
    'profile-discord',
    [
        'key' => 'profile-youtube',
        'type' => 'text',
        'label' => 'YouTube',
        'name' => 'YouTube',
        'placeholder' => 'Псевдоним',
        'help' => 'Ваш @псевдоним без символа @',
        'url' => 'https://www.youtube.com/@$1',
        'maxlength' => 64,
        'icon' => '<?xml version="1.0" encoding="UTF-8"?><svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000"><path d="M14 12L10.5 14V10L14 12Z" fill="#000000" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M2 12.7075V11.2924C2 8.39705 2 6.94939 2.90549 6.01792C3.81099 5.08645 5.23656 5.04613 8.08769 4.96549C9.43873 4.92728 10.8188 4.8999 12 4.8999C13.1812 4.8999 14.5613 4.92728 15.9123 4.96549C18.7634 5.04613 20.189 5.08645 21.0945 6.01792C22 6.94939 22 8.39705 22 11.2924V12.7075C22 15.6028 22 17.0505 21.0945 17.9819C20.189 18.9134 18.7635 18.9537 15.9124 19.0344C14.5613 19.0726 13.1812 19.1 12 19.1C10.8188 19.1 9.43867 19.0726 8.0876 19.0344C5.23651 18.9537 3.81097 18.9134 2.90548 17.9819C2 17.0505 2 15.6028 2 12.7075Z" stroke="#000000" stroke-width="1.5"></path></svg>',
    ],
    [
        'key' => 'profile-telegram',
        'type' => 'text',
        'label' => 'Telegram',
        'name' => 'Telegram',
        'placeholder' => 'Имя пользователя',
        'help' => 'Ваше имя пользователя без символа @',
        'url' => 'https://t.me/$1',
        'maxlength' => 64,
        'icon' => '<?xml version="1.0" encoding="UTF-8"?><svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000"><path d="M21 5L2 12.5L9 13.5M21 5L18.5 20L9 13.5M21 5L9 13.5M9 13.5V19L12.2488 15.7229" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
    ],
    'profile-show-globalgroups',
    'profile-show-globaledits',
];

/**
 * Extension:VisualEditor
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-VisualEditor
 */
// Enable Edit Check
// @see https://www.mediawiki.org/wiki/Edit_check
$wgVisualEditorEditCheck = true;
// Enable 2017 Wikitext Editor
// @see https://www.mediawiki.org/wiki/2017_wikitext_editor
$wgVisualEditorEnableWikitext = true;
$wgVisualEditorUseSingleEditTab = true;
$wgDefaultUserOptions["visualeditor-enable"] = 1;
// Default to new source editor
$wgDefaultUserOptions["visualeditor-newwikitext"] = 1;
// It is enabled by default, no need to add it to preferences
$wgPrefs[] = "visualeditor-enable";

/**
 * Extension:WikiEditor
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-WikiEditor
 */
$wgWikiEditorRealtimePreview = true;

/**
 * Extension:WikiSEO
 *
 * @see https://github.com/wikimedia/mediawiki-extensions-WikiSEO
 */
$wgWikiSeoDefaultLanguange = "ru";
// Disable wgLogo as fallback image in embed
$wgWikiSeoDisableLogoFallbackImage = true;
// Use TextExtracts description
$wgWikiSeoEnableAutoDescription = true;
$wgWikiSeoTryCleanAutoDescription = true;

/**
 * Extension:Yappin
 *
 * @see https://github.com/weirdgloop/mediawiki-extensions-Yappin
*/
$wgCommentsUseAbuseFilter = true;
$wgGroupPermissions['user']['comments'] = true;
$wgGroupPermissions['sysop']['comments-manage'] = true;
$wgCommentsShowOnMainPage = false;

/**
 * Skin:Citizen
 *
 * @see https://github.com/StarCitizenTools/mediawiki-skins-Citizen
 */
$wgCitizenPreview = 4;
// Default to dark theme
$wgCitizenThemeDefault = "dark";

/**
 * Enable WikiDiff2
 */
$wgDiffEngine = "wikidiff2";

/**
 * User group permission settings
 */
// Block creating accounts using the API
$wgAPIModules["createaccount"] = "ApiDisabled";
// 6 account creations per day (captcha failures are counted as a creation)
$wgAccountCreationThrottle = [
    [
        "count" => 6,
        "seconds" => 86400, // 1 day
    ],
];

// Anon
// Enable temp account
$wgAutoCreateTempUser['enabled'] = true;
// Disable all anon edits as anti-spam measure
// $wgGroupPermissions["*"]["edit"] = false;
// $wgGroupPermissions["*"]["createpage"] = false;
// $wgGroupPermissions["*"]["createtalk"] = false;
// Restrict Special:ThumbroTest to editors or above
$wgGroupPermissions["*"]["thumbro-test"] = false;

// Registered user
$wgGroupPermissions["user"]["edit"] = true;
$wgGroupPermissions["user"]["purge"] = false;
// Need to be verified to create page as an anti-spam measure
// Sometimes bots get through registration and will create spam page endlessly
$wgGroupPermissions["user"]["createpage"] = false;
$wgGroupPermissions["user"]["createtalk"] = false;
// Disable minor edits for easier patrol
$wgGroupPermissions["user"]["minoredit"] = false;
$wgGroupPermissions["user"]["move"] = false;
$wgGroupPermissions["user"]["movefile"] = false;
$wgGroupPermissions["user"]["move-categorypages"] = false;
$wgGroupPermissions["user"]["move-rootuserpages"] = false;
$wgGroupPermissions["user"]["move-subpages"] = false;
$wgGroupPermissions["user"]["reupload"] = false;
$wgGroupPermissions["user"]["reupload-own"] = false;

// Autoconfirmed users
$wgAutoConfirmAge = 86400 * 3; // three days
$wgAutoConfirmCount = 20;
// Enable page upload, create page permission for confirmed users
$wgGroupPermissions["autoconfirmed"]["upload_by_url"] = true;
$wgGroupPermissions["autoconfirmed"]["createpage"] = true;
$wgGroupPermissions["autoconfirmed"]["createtalk"] = true;

// Verified users
$wgGroupPermissions["Verified"] = $wgGroupPermissions["autoconfirmed"];
$wgGroupPermissions["Verified"]["skipcaptcha"] = true;
$wgGroupPermissions["Verified"]["purge"] = true;
$wgGroupPermissions["Verified"]["reupload"] = true;
$wgGroupPermissions["Verified"]["reupload-own"] = true;
$wgGroupPermissions["Verified"]["minoredit"] = true;

// Trusted users
$wgAutopromoteOnce["onEdit"]["Trusted"] = [
    "&",
    [APCOND_EDITCOUNT, 300],
    [APCOND_INGROUPS, "Verified"],
    ["!", [APCOND_INGROUPS, "Editor"]],
    ["!", [APCOND_INGROUPS, "sysop"]],
    ["!", [APCOND_INGROUPS, "bot"]],
];
$wgGroupPermissions["Trusted"] = $wgGroupPermissions["Verified"];
$wgGroupPermissions["Trusted"]["patrol"] = true;
$wgGroupPermissions["Trusted"]["move"] = true;
$wgGroupPermissions["Trusted"]["movefile"] = true;
$wgGroupPermissions["Trusted"]["move-categorypages"] = true;
$wgGroupPermissions["Trusted"]["sendemail"] = true;
$wgGroupPermissions["Trusted"]["commlink-edit"] = true;
$wgGroupPermissions["Trusted"]["issue-edit"] = true;
$wgGroupPermissions["Trusted"]["projmgmt-edit"] = true;
$wgGroupPermissions["Trusted"]["move-subpages"] = true;
$wgGroupPermissions["Trusted"]["template-edit"] = true;

// Editors
// A hybrid of bureaucrat and sysops, some kind of moderators without site permissions
$wgGroupPermissions["Editor"] = $wgGroupPermissions["Trusted"];
$wgGroupPermissions["Editor"]["rollback"] = true;
$wgGroupPermissions["Editor"]["protect"] = true;
$wgGroupPermissions["Editor"]["editprotected"] = true;
$wgGroupPermissions["Editor"]["suppressredirect"] = true;
$wgGroupPermissions["Editor"]["autopatrol"] = true;
$wgGroupPermissions["Editor"]["pagetranslation"] = true;
$wgGroupPermissions["Editor"]["delete"] = true;
$wgGroupPermissions["Editor"]["bigdelete"] = true;
$wgGroupPermissions["Editor"]["deletedhistory"] = true;
$wgGroupPermissions["Editor"]["deletedtext"] = true;
$wgGroupPermissions["Editor"]["block"] = true;
$wgGroupPermissions["Editor"]["undelete"] = true;
$wgGroupPermissions["Editor"]["mergehistory"] = true;
$wgGroupPermissions["Editor"]["browsearchive"] = true;
$wgGroupPermissions["Editor"]["noratelimit"] = true;
$wgGroupPermissions["Editor"]["move-rootuserpages"] = true;
$wgGroupPermissions["Editor"]["thumbro-test"] = true;

// Sysop
$wgGroupPermissions["sysop"] = $wgGroupPermissions["Editor"];
$wgGroupPermissions["sysop"]["userrights"] = true;
$wgGroupPermissions["sysop"]["siteadmin"] = true;
$wgGroupPermissions["sysop"]["checkuser"] = true;
$wgGroupPermissions["sysop"]["checkuser-log"] = true;
$wgGroupPermissions["sysop"]["renameuser"] = true;
// To grant sysops permissions to edit interwiki data
$wgGroupPermissions["sysop"]["interwiki"] = true;

/**
 * MediaWiki hooks
 * Site-specific stuff that does not make sense to go into an extension
 *
 * @see https://www.mediawiki.org/wiki/Manual:Hooks
 */

/** @see https://www.mediawiki.org/wiki/Manual:Hooks/ThumbnailBeforeProduceHTML */
$sctHasSetImageEager = false;
$wgHooks["ThumbnailBeforeProduceHTML"][] = function (
    $thumbnail,
    &$attribs,
    &$linkAttribs,
) {
    /**
     * Eager load the first image on the page
     * Currently we don't have a reliable way to set which image,
     * so we will just grab the first image with 400px as width,
     * since it is used by infoboxes usually.
     */
    global $sctHasSetImageEager;
    if ($sctHasSetImageEager === false) {
        /**
         * Check if the image is a LCP image
         * 1. Make sure that the image has the mw-file-description class
         * 2. Make sure that the image has 400px image width (i.e. infobox image)
         */
        $isLCPImage =
            strpos($linkAttribs["class"] ?? "", "mw-file-description") !==
                false && $attribs["width"] === 400;
        if ($isLCPImage) {
            unset($attribs["loading"]);
            $sctHasSetImageEager = true;
        }
    }
    return true;
};

/** @see https://www.mediawiki.org/wiki/Manual:$wgNoFollowLinks */
$wgHooks["HtmlPageLinkRendererEnd"][] = function (
    $linkRenderer,
    $target,
    $isKnown,
    &$text,
    &$attribs,
    &$ret,
) {
    // Append rel="nofollow" to red links to avoid unnecessary crawler traffic
    if (!$isKnown && preg_match("/\bnew\b/S", $attribs["class"] ?? "")) {
        $attribs["rel"] = "nofollow";
    }
    return true;
};

/**
 * Strengthen Content Security Policy implementation in MW
 *
 * @see https://doc.wikimedia.org/mediawiki-core/REL1_43/php/classMediaWiki_1_1HookContainer_1_1HookRunner.html#a1bef6100adfea8724efc95072a9c9ef2
 */
$wgHooks["ContentSecurityPolicyDirectives"][] = function (
    &$directives,
    &$policyConfig,
    $mode,
) {
    $directives[] = "base-uri 'self'"; // Special:Upload requires base-uri 'self'
    $directives[] = "form-action 'self'";
    $directives[] = "frame-ancestors 'none'";
};
