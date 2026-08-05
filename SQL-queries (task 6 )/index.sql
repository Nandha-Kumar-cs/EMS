-- heighest salary 
select max(salary) as "highest salary" from employees ;  

-- second highest salary 
select  e1.salary from employee e1 where 1 = ( select count(*) from employee e2 where  e1.salary < e2.salary ) ; 

-- employee count by department
select department , count(*) as total_employees  
from employee 
group by department 

-- employee joined in the current month 
select  * from employee 
where month(date_of_joining) = month(curdate()) and year(date_of_joining) = year(curdate()) ;  

-- duplicate emails 
select emails , count(*) as total_count 
from employee 
group by emails 
having total_count > 1 ; 
