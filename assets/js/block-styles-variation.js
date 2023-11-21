wp.domReady(() => {

    wp.blocks.registerBlockStyle("core/spacer", {
        name: "spacer-128",
        label: "Spacer 128",
        isDefault: false,
    });

    wp.blocks.registerBlockStyle("core/spacer", {
        name: "spacer-96",
        label: "Spacer 96",
        isDefault: false,
    });

    wp.blocks.registerBlockStyle("core/spacer", {
        name: "spacer-64",
        label: "Spacer 64",
        isDefault: false,
    });

    wp.blocks.registerBlockStyle("core/list", {
        name: "office-amenities",
        label: "Office Amenities",
        isDefault: false,
    });

    wp.blocks.registerBlockStyle("core/list", {
        name: "hourly-rate",
        label: "Hourly Rate List",
        isDefault: false,
    });

    wp.blocks.registerBlockStyle("core/list", {
        name: "footer-links",
        label: "Footer Links",
        isDefault: false,
    });

    wp.blocks.registerBlockStyle("core/details", {
        name: "services-accordion",
        label: "Service Accordion",
        isDefault: false,
    });

    // Media Text
    wp.blocks.registerBlockStyle("core/media-text", {
        name: "media-variation-1",
        label: "Card Variation 1",
        isDefault: false,
    });

    // sEARCH   
    wp.blocks.registerBlockStyle("core/search", {
        name: "header-search",
        label: "Header Search",
        isDefault: false,
    });

    // PAGINATION   
    wp.blocks.registerBlockStyle("core/query-pagination", {
        name: "variation-1",
        label: "Variation 1",
        isDefault: false,
    });

    wp.blocks.registerBlockStyle("core/button", {
        name: "button-arrow",
        label: "Button with Arrow",
        isDefault: false,
    });
});
