var config = {
        container: "#custom-colored",

        nodeAlign: "BOTTOM",
        
        connectors: {
            type: 'step'
        },
        node: {
            HTMLclass: 'chart_level'
        }
    },
    vice = {
        HTMLclass : 'color_vice',
        text: {
            name: "Mr. ĐỖ QUANG KHA",
            title: "VICE CHAIRMAN",
        }
    },
    ceo = {
        parent: vice,
        HTMLclass : 'color_ceo',
        text: {
            name: "Mr. KHƯƠNG QUỐC ĐỐNG",
            title: "C.E.O",
        }
    },
    //line 3
    sale_mn_temp = {
        parent: ceo,
        HTMLclass : 'color_dr',
        text: {
            name: "Mr. NGUYỄN VĂN NAM",
            title: "SALES MANAGER",
        }
        
    }, 
    retail_mn_temp = {
        parent: ceo,
        HTMLclass : 'color_dr',
        text: {
            name: "Mr. NGUYỄN VĂN NAM",
            title: "SALES MANAGER",
        }
    },
    gtm_mn_temp = {
        parent: ceo,
        HTMLclass : 'color_dr',
        text: {
            name: "Mr. NGUYỄN VĂN NAM",
            title: "SALES MANAGER",
        }
    },
    gtm_mn = {
        parent: ceo,
        HTMLclass : 'color_dr',
        text: {
            name: "GTM MANAGER",
            title: "GTM MANAGER",
        }
    },
    mtk_dr = {
        parent: ceo,
        HTMLclass : 'color_dr',
        text: {
            name: "MR. GLEN",
            title: "MKT DIRECTOR",
        }
    },
    operations_dr = {
        parent: ceo,
        HTMLclass : 'color_dr',
        stackChildren: true,
        text: {
            name: "MS. TÔ THỊ THU THUỶ",
            title: "OPERATIONS DIRECTOR",
        }
    },
    finance_dr = {
        parent: ceo,
        HTMLclass : 'color_dr',
        text: {
            name: "MS. NGÔ LỆ THUÝ",
            title: "FINANCE DIRECTOR",
        }
    },
    // line 4
    sale_mn = {
        parent: sale_mn_temp,
        stackChildren: true,
        HTMLclass : 'color_child',
        text: {
            name: "MR. NGUYỄN VĂN NAM",
            title: "SALES MANAGER",
        }
    },
    retail_mn = {
        parent: retail_mn_temp,
        stackChildren: true,
        HTMLclass : 'color_child',
        text: {
            name: "MR. NGUYỄN VĂN HIỆP",
            title: "RETAIL MANAGER",
        }
    },
    gtm_mn = {
        parent: gtm_mn_temp,
        stackChildren: true,
        HTMLclass : 'color_child',
        text: {
            name: "GTM MANAGER",
            title: "GTM MANAGER",
        }
    },
    mkt_mn = {
        parent: mtk_dr,
        HTMLclass : 'color_child',
        text: {
            name: "MS. NGUYỄN T.N.ANH",
            title: "MKT MANAGER",
        }
    },
    finance_dr1 = {
        parent: finance_dr,
        HTMLclass : 'color_dr',
        text: {
            name: "MS. NGÔ LỆ THUÝ",
            title: "FINANCE DIRECTOR",
        }
    },
    //child sales manager :sale_mn
    area_mn = {
        parent: sale_mn,
        HTMLclass : 'color_child',
        text: {
            name: "10 REGIONAL MANAGER",
            title: "AREA MANAGER",
        }
    },
    ka_mn = {
        parent: sale_mn,
        HTMLclass : 'color_child',
        text: {
            name: "MS. CHÂU T.THUÝ HẰNG",
            title: "KA MANAGER",
        }
    },
    online_sale_mn = {
        parent: sale_mn,
        HTMLclass : 'color_child',
        text: {
            name: "MR. TY TY",
            title: "ONLINE SALES MANAGER",
        }
    },
    operation_mn = {
        parent: sale_mn,
        HTMLclass : 'color_child',
        text: {
            //name: "",
            title: "OPERATOR MANAGER",
        }
    },
    //child retail manager :retail_mn
    trade_mkt_mn = {
        parent: retail_mn,
        HTMLclass : 'color_child',
        text: {
            name: "MR. NGUYỄN VĂN HIỆP",
            title: "TRADE MKT MANAGER",
        }
    },
    brandshop_mn = {
        parent: retail_mn,
        HTMLclass : 'color_child',
        text: {
            name: "MR. NGUYỄN VIỆT MINH TÂM",
            title: "BRANDSHOP MANAGER",
        }
    },
    traning_mn = {
        parent: retail_mn,
        HTMLclass : 'color_child',
        text: {
            name: "MR. NGUYỄN VIỆT MINH TÂM",
            title: "BRANDSHOP MANAGER",
        }
    },
    channel_mkt_ld = {
        parent: retail_mn,
        HTMLclass : 'color_child',
        text: {
            name: "MS. LÊ THUỲ DƯƠNG",
            title: "CHANNEL MKT LEADER",
        }
    },
    // child mkt director: mtk_dr
    mkt_mn = {
        parent: mtk_dr,
        HTMLclass : 'color_child',
        text: {
            name: "MS. NGUYỄN THỊ NHẬT ANH",
            title: "MKT MANAGER",
        }
    },
    //child operation director : operations_dr
    hr_dr = {
        parent: operations_dr,
        HTMLclass : 'color_dr',
        text: {
            name: "MS. TÔ THỊ THU THUỶ",
            title: "HR DIRECTOR",
        }
    },
    service_mn = {
        parent: operations_dr,
        HTMLclass : 'color_child',
        text: {
            name: "MR. LÝ TIỂU PHƯƠNG",
            title: "SERVICE MANAGER",
        }
    },
    call_center_mn = {
        parent: operations_dr,
        HTMLclass : 'color_child',
        text: {
            name: "MS. NGUYỄN T.N.DUNG",
            title: "CALL CENTER MANAGER",
        }
    },
    logistics_mn = {
        parent: operations_dr,
        HTMLclass : 'color_child',
        text: {
            name: "MR. TRẦN NGUYÊN DUY",
            title: "LOGISTICS MANAGER",
        }
    },
    purchasing_mn = {
        parent: operations_dr,
        HTMLclass : 'color_child',
        text: {
            name: "MS. TRẦN THỊ HIỀN",
            title: "PURCHASING MANAGER",
        }
    },
    test_leader = {
        parent: operations_dr,
        HTMLclass : 'color_child',
        text: {
            name: "MR. XANH VĨNH AN",
            title: "TEST LEADER",
        }
    },
    technology_mn = {
        parent: operations_dr,
        HTMLclass : 'color_child',
        text: {
            name: "MR. ĐỖ MINH TÂM",
            title: "TECHNOLOGY MANAGER",
        }
    },
    chart_config = [
        config,
        vice,
        ceo,
        sale_mn_temp,
        retail_mn_temp,
        gtm_mn_temp,
        gtm_mn,
        mtk_dr,
        operations_dr,
        finance_dr,
        sale_mn,
        retail_mn,
        gtm_mn,
        mkt_mn,
        hr_dr,
        finance_dr1,
        area_mn,
        ka_mn,
        online_sale_mn,
        operation_mn,
        trade_mkt_mn,
        brandshop_mn,
        traning_mn,
        channel_mkt_ld,
        mkt_mn,
        service_mn,
        logistics_mn,
        call_center_mn,
        logistics_mn,
        purchasing_mn,
        test_leader,
        technology_mn,
    ];
    

    // Another approach, same result
    // JSON approach

/*
    var chart_config = {
        chart: {
            container: "#custom-colored",

            nodeAlign: "BOTTOM",

            connectors: {
                type: 'step'
            },
            node: {
                HTMLclass: 'nodeExample1'
            }
        },
        nodeStructure: {
            text: {
                name: "Mark Hill",
                title: "Chief executive officer",
                contact: "Tel: 01 213 123 134",
            },
            image: "../headshots/2.jpg",
            children: [
                {   
                    text:{
                        name: "Joe Linux",
                        title: "Chief Technology Officer",
                    },
                    image: "../headshots/1.jpg",
                    HTMLclass: 'light-gray',
                    children: [
                        {
                            text:{
                                name: "Ron Blomquist",
                                title: "Chief Information Security Officer"
                            },
                            HTMLclass: 'light-gray',
                            image: "../headshots/8.jpg"
                        },
                        {
                            text:{
                                name: "Michael Rubin",
                                title: "Chief Innovation Officer",
                                contact: "we@aregreat.com"
                            },
                            HTMLclass: 'light-gray',
                            image: "../headshots/9.jpg"
                        }
                    ]
                },
                {
                    childrenDropLevel: 2,
                    text:{
                        name: "Linda May",
                        title: "Chief Business Officer",
                    },
                    HTMLclass: 'blue',
                    image: "../headshots/5.jpg",
                    children: [
                        {
                            text:{
                                name: "Alice Lopez",
                                title: "Chief Communications Officer"
                            },
                            HTMLclass: 'blue',
                            image: "../headshots/7.jpg"
                        },
                        {
                            text:{
                                name: "Mary Johnson",
                                title: "Chief Brand Officer"
                            },
                            HTMLclass: 'blue',
                            image: "../headshots/4.jpg"
                        },
                        {
                            text:{
                                name: "Kirk Douglas",
                                title: "Chief Business Development Officer"
                            },
                            HTMLclass: 'blue',
                            image: "../headshots/11.jpg"
                        }
                    ]
                },
                {
                    text:{
                        name: "John Green",
                        title: "Chief accounting officer",
                        contact: "Tel: 01 213 123 134",
                    },
                    HTMLclass: 'gray',
                    image: "../headshots/6.jpg",
                    children: [
                        {
                            text:{
                                name: "Erica Reel",
                                title: "Chief Customer Officer"
                            },
                            link: {
                                href: "http://www.google.com"
                            },
                            HTMLclass: 'gray',
                            image: "../headshots/10.jpg"
                        }
                    ]
                }
            ]
        }
    };

*/