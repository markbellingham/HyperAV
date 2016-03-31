SELECT 	stFName AS "First Name",stLName AS "Last Name",loName AS "Location" 
FROM 	hyperav_staff join hyperav_location ON hyperav_location.locationid = hyperav_staff.locationid
WHERE 	hyperav_location.loName = 'HyperAv London';

SELECT 	su.suName AS "Supplier Name", pr.prName AS "Product Name", sod.stOrderQuantity AS "Order Quantity" 
FROM 	hyperav_stockorderdetails sod JOIN hyperav_stock st ON sod.stockID = st.stockID JOIN hyperav_products pr ON st.prModelNo = pr.prModelNo JOIN hyperav_supplier su ON sod.supplierID = su.supplierID
WHERE 	sod.stDeliveryDate IS NULL;

SELECT 	CONCAT(cu.cuFName, ' ', cu.cuLName) AS "Customer Name", o.orDate AS "Order Date", o.orTotal AS "Order Total" 
FROM 	hyperav_orders o JOIN hyperav_customer cu ON o.customerID = cu.customerID 
WHERE 	o.ortotal > 100 
ORDER BY o.orTotal;

SELECT 	SUM(orTotal) AS "Total Sales", lo.loName AS "Location" 
FROM 	hyperav_orders o JOIN hyperav_staff st ON o.staffID = st.staffID JOIN hyperav_location lo ON st.locationID = lo.locationID 
GROUP BY lo.locationID;

SELECT 	AVG(orTotal) AS "Average Sales"
FROM 	hyperav_orders o JOIN hyperav_staff st ON o.staffID = st.staffID JOIN hyperav_location lo ON st.locationID = lo.locationID 
WHERE 	lo.locationID = 4;



SELECT 	prName, prPrice, odQuantity, orDate, orTotal, orDeliverDate, orPaymentMethod FROM hyperav_orders o JOIN hyperav_orderdetails od ON o.orderID = od.orderID JOIN hyperav_stock st ON od.stockID = st.stockID JOIN hyperav_products pr ON st.prModelNo = pr.prModelNo WHERE o.customerID = 2;