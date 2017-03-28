SELECT 	stFName AS "First Name",stLName AS "Last Name",loName AS "Location" 
FROM 	hyperAV_staff join hyperAV_location ON hyperAV_location.locationid = hyperAV_staff.locationid
WHERE 	hyperAV_location.loName = 'HyperAv London';

SELECT 	su.suName AS "Supplier Name", pr.prName AS "Product Name", sod.stOrderQuantity AS "Order Quantity" 
FROM 	hyperAV_stockorderdetails sod JOIN hyperAV_stock st ON sod.stockID = st.stockID JOIN hyperAV_products pr ON st.prModelNo = pr.prModelNo JOIN hyperAV_supplier su ON sod.supplierID = su.supplierID
WHERE 	sod.stDeliveryDate IS NULL;

SELECT 	CONCAT(cu.cuFName, ' ', cu.cuLName) AS "Customer Name", o.orDate AS "Order Date", o.orTotal AS "Order Total" 
FROM 	hyperAV_orders o JOIN hyperAV_customer cu ON o.customerID = cu.customerID 
WHERE 	o.ortotal > 100 
ORDER BY o.orTotal;

SELECT 	SUM(orTotal) AS "Total Sales", lo.loName AS "Location" 
FROM 	hyperAV_orders o JOIN hyperAV_staff st ON o.staffID = st.staffID JOIN hyperAV_location lo ON st.locationID = lo.locationID 
GROUP BY lo.locationID;

SELECT 	AVG(orTotal) AS "Average Sales"
FROM 	hyperAV_orders o JOIN hyperAV_staff st ON o.staffID = st.staffID JOIN hyperAV_location lo ON st.locationID = lo.locationID 
WHERE 	lo.locationID = 4;



SELECT 	prName, prPrice, odQuantity, orDate, orTotal, orDeliverDate, orPaymentMethod FROM hyperAV_orders o JOIN hyperAV_orderdetails od ON o.orderID = od.orderID JOIN hyperAV_stock st ON od.stockID = st.stockID JOIN hyperAV_products pr ON st.prModelNo = pr.prModelNo WHERE o.customerID = 2;