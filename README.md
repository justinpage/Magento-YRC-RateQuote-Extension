Magento YRC Rate Quote Extension
==============================
YRC Rate Quote for real-time shipping rates from YRC SOAP.

Requirements
------------
- User ID
- Password
- Base URL
- Business Role
- Business ID
- Origin Country
- Origin Postal Code
- Origin City
- Payment Terms
- Line Item Nmfc Class
- Service Class
- Pickup Increment
- Type Query
- Maximum package weight supported 
- Sort order in shipping methods list

Install
-------
1. Copy `app/code/community/YRC` to `app/code/community`
2. Copy `app/etc/modules/YRC_RateQuote.xml` to `app/etc/modules`
3. Head on over to `System > Configuratiom > Sales > Shipping Methods > YRC Rate`
4. Enter the required parameters provided to you by [YRC Rate Quote SOAP Docs](http://yrc.com/api/).
5. Save.

Tree Structure
--------------
```bash
app
├── code
│   └── community
│       └── YRC
│           └── RateQuote
│               ├── Helper
│               │   ├── Data.php
│               │   ├── JSON.php
│               │   └── YRC.php
│               ├── Model
│               │   └── Carrier.php
│               └── etc
│                   ├── config.xml
│                   └── system.xml
└── etc
    └── modules
	        └── YRC_RateQuote.xml
```

QA
--
*Why do I have to enter my base URL?* 

YRC has a very stict policy on their implementation guide. This repository will
respect that protection. If you have an account, obtaining the necessary
information is extremely easy. If you find yourself having difficulty in
determing the proper base url, please create an [issue][github].

[github]: https://github.com/KLVTZ/Magento-YRC-RateQuote-Extension/issues

*Why do you only support weight as the primary condition for a quote?*

As of January 2015, sendiong cubic dimensions, in addition to weight, did not
alter the results. That is, sending the weight alone matched the same results
returned when cubic dimensions where provided. If you have determined edge-cases
where this may contradict, please create an [issue][github]. My goal is to
support cubic dimensions for a more discrete implementation in the near future. 

[github]: https://github.com/KLVTZ/Magento-YRC-RateQuote-Extension/issues

Documentation
-------------
This plugin uses [YRC RateQuote SOAP]. Unfortunately, I cannot provide the
implementation guide as it is under a non-disclosure agreement. You can get one
directly from [YRC API][api].

[api]: http://yrc.com/api/


License
-------
It's MIT licensed. See the [LICENSE][license] file for more information.

[license]: /LICENSE
