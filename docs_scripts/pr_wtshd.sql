CREATE OR REPLACE FUNCTION wtshd() returns void as $$
DECLARE   
    i record;
    j record;
    num integer;
    my_geom geometry;   
    my_area numeric(16,3);    
BEGIN
   FOR i IN  select distinct  subcuenca_  from pr_subwtshds_temp LOOP
      select into num count(*) from pr_subwtshds_temp where subcuenca_ = i.subcuenca_;
         my_geom := NULL;
         RAISE NOTICE 'zone is %,  and count is %', i.subcuenca_,  num;
         FOR j IN SELECT wkb_geometry FROM pr_subwtshds_temp WHERE subcuenca_ = i.subcuenca_ LOOP
            if my_geom IS NULL 
	       THEN	    
               my_geom := j.wkb_geometry;
	    END IF;
	    SELECT INTO my_geom multi((geomunion(my_geom, j.wkb_geometry)));
         END LOOP;	
	 insert into pr_subwtshds(subcuenca, wkb_geometry) values(i.subcuenca_, my_geom);
         select into my_area area(my_geom);
         RAISE NOTICE 'table pr_wtshds updated, area is %', my_area;	    
   END LOOP;
   Return;
END;
$$ LANGUAGE plpgsql;