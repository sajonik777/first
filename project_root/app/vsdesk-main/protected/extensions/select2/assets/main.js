var mydata = [
    {id:1, text:"USA", inc:[
        {text:"west", inc:[
            {id:111, text:"California", inc:[
                {id:1111, text:"Los Angeles", inc:[
                {id:11111, text:"Hollywood"}
                ]},
                {id:1112, text:"San Diego"}
            ]},
            {id:112, text:"Oregon"}
        ]}
    ]},
    {id:2, text:"India"},
    {id:3, text:"中国"}
    ];
    $("#sel_1").select2ToTree({treeData: {dataArr: mydata}, maximumSelectionLength: 3});