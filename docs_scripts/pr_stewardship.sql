CREATE OR REPLACE FUNCTION steward() returns void as $$
DECLARE   
    i record;
    j record;
    num integer;
    my_geom geometry;   
    my_area numeric(16,3);    
BEGIN
   FOR i IN  select distinct  m_class_co, mgmt_name from pr_stewardship LOOP
      select into num count(*) from pr_stewardship where m_class_co = i.m_class_co;      
      if i.m_class_co <> 0
         then
         my_geom := NULL;
         RAISE NOTICE 'gapman code is %,  and count is %', i.m_class_co,  num;
         FOR j IN SELECT wkb_geometry FROM pr_stewardship WHERE m_class_co = i.m_class_co LOOP
            if my_geom IS NULL 
	       THEN	    
               my_geom := j.wkb_geometry;
	    END IF;
	    SELECT INTO my_geom multi((geomunion(my_geom, j.wkb_geometry)));
         END LOOP;	
	 insert into pr_manage(m_class_co, mgmt_name, wkb_geometry) values(i.m_class_co, i.mgmt_name, my_geom);
         select into my_area area(my_geom);
         RAISE NOTICE 'table pr_manage updated, area is %', my_area;	
      END if;
   END LOOP;
   Return;
END;
$$ LANGUAGE plpgsql;