// https://atlas.atdw-online.com.au/api/atlas/products?key=123456789101112&cats=ACCOMM&out=json
const response = {
    status: 0,
    numberOfResults: 10912,
    queryTime: 9,
    products: 
    [
        {
            productId: "5bac22d4cd8d68ad0493af71",
            productNumber: "AU0186836",
            status: "ACTIVE",
            owningOrganisationId: "5bac20d8075cacbd286bb75f",
            owningOrganisationNumber: "OR0018865",
            owningOrganisationName: "L J HOOKER NELSON BAY",
            productName: "'Salt Wood' Cabin Style Retreat",
            productDescription: "Large house to accommodate the whole extended family.
            Come and enjoy this beach house located in a prime position of Anna Bay. This large two story house comfortably accommodates up to 14 guests and is situated only a short walk to Birubi Beach, cafes and local stores. It is time you enjoy the company of your family and friends and soak up the hassle-free atmosphere of Anna Bay.
            Whether you stay indoors to entertain or are heading out to enjoy the sun and surf of Anna Bay, you will no doubt enjoy your time there at Port Stephens.",
            productCategoryId: "ACCOMM",
            productImage: "https://assets.atdw-online.com.au/images/93b82613c9344f1c8b789abed1ea73ab.jpeg?rect=129,0,2048,1536&w=280&h=210&rot=360",
            boundary: "-32.7809604,152.08037109999998",
            addresses: 
            [
                {
                    address_type: "PHYSICAL",
                    address_line: "42B James Paterson Street",
                    address_line2: "",
                    address_line3: "",
                    city: "Anna Bay",
                    state: "NSW",
                    postcode: "2316",
                    country: "Australia",
                    area: 
                    [
                        "Port Stephens"
                    ],
                    region: 
                    [
                        "North Coast"
                    ]
                }
            ],
            score: 1,
            productPixelURL: "https://atlas.atdw-online.com.au/pixel?productId=5bac22d4cd8d68ad0493af71&distributorId=56b1eb9344feca3df2e320c8&language=en&syndicationMethod=API"
            }
    ]
}



// https://atlas.atdw-online.com.au/api/atlas/products?key=123456789101112&st=NSW&&cats=ACCOMM&out=json search products
/**
 * out = json
 * st = state
 * ar = area
 * rg = region
 * cats = catalogs
 * 
 */


// https://atlas.atdw-online.com.au/api/atlas/areas?key=123456789101112&st=NSW&out=json  all areas in NSW, response is array of area objects

const area1 = {AreaId: "29000030",
Code: "KAT",
Name: "Katoomba",
Type: "LOCAL",
DomesticRegionId: 29000008,
StateId: 9000002,
StateCode: "NSW"}

// get regions https://atlas.atdw-online.com.au/api/atlas/regions?key=123456789101112&st=NSW&ar=Port%20Stephens&&pge=3&size=10&out=json