CREATE OR REPLACE FUNCTION zones() returns void as $$
DECLARE   
    i record;
    j record;
    num integer;
    my_geom geometry;   
    my_area numeric(16,3);    
BEGIN
   FOR i IN  select distinct  zone_desc from pr_zones LOOP
      select into num count(*) from pr_zones where zone_desc = i.zone_desc;
         my_geom := NULL;
         RAISE NOTICE 'zone is %,  and count is %', i.zone_desc,  num;
         FOR j IN SELECT wkb_geometry FROM pr_zones WHERE zone_desc = i.zone_desc  LOOP
            if my_geom IS NULL 
	       THEN	    
               my_geom := j.wkb_geometry;
	    END IF;
	    SELECT INTO my_geom multi((geomunion(my_geom, j.wkb_geometry)));
         END LOOP;	
	 insert into pr_life_zones(zone_desc, wkb_geometry) values(i.zone_desc, my_geom);
         select into my_area area(my_geom);
         RAISE NOTICE 'table pr_life_zones updated, area is %', my_area;	    
   END LOOP;
   Return;
END;
$$ LANGUAGE plpgsql;