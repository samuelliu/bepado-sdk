Feature: Interactions between shops on a purchase

    Scenario: Succussful purchase
        Given The product is listed as available
          And A customer adds a product from remote shop 1 to basket
          And The product is available in 1 shop
         When The Customer checks out
         Then The customer will receive the product

    Scenario: Succussful purchase from multiple shops
        Given The product is listed as available
          And A customer adds a product from remote shop 1 to basket
          And A customer adds a product from remote shop 2 to basket
          And The products are available in 2 shops
         When The Customer checks out
         Then The customer will receive the products

    Scenario: Product is not available any more in remote shop
        Given The product is listed as available
          And A customer adds a product from remote shop 1 to basket
          And The product is not available in remote shop
         When The Customer views the order overview
         Then The customer is informed about the unavailability

    Scenario: Product price changed in remote shop
        Given The product is listed as available
          And A customer adds a product from remote shop 1 to basket
          And The product price has changed in the remote shop
         When The Customer views the order overview
         Then The customer is informed about the changed price

    Scenario: Product is reserved in remote shop
        Given The product is listed as available
          And A customer adds a product from remote shop 1 to basket
          And The product data is still valid
         When The Customer views the order overview
         Then The product is reserved in the remote shop

    Scenario: The Buy process fails
        Given The product is listed as available
          And A customer adds a product from remote shop 1 to basket
          And The product changes availability between check and purchase
         When The Customer checks out
         Then The buy process fails and customer is informed about this

    Scenario: The Buy process fails
        Given The product is listed as available
          And A customer adds a product from remote shop 1 to basket
          And The product is available in 1 shop
          And The remote shop denies the buy
         When The Customer checks out
         Then The buy process fails

    Scenario: The Buy succeeds and everything is logged
        Given The product is listed as available
          And A customer adds a product from remote shop 1 to basket
          And The product is available in 1 shop
         When The Customer checks out
         Then The local shop logs the transaction with Mosaic
          And The remote shop logs the transaction with Mosaic

