CREATE OR REPLACE FUNCTION muni() returns void as $$
DECLARE   
    i record;
    j record;
    num integer;
    my_geom geometry;   
    my_area numeric(16,3);    
BEGIN
   FOR i IN  select distinct  municipio from pr_mun LOOP 
         select into num count(*) from pr_mun where municipio = i.municipio;  
         RAISE NOTICE 'muni is %,  and count is %', i.municipio,  num;  
         my_geom := NULL;   
         FOR j IN SELECT wkb_geometry FROM pr_mun WHERE municipio = i.municipio LOOP
            if my_geom IS NULL 
	       THEN	    
               my_geom := j.wkb_geometry;
	    END IF;
	    SELECT INTO my_geom multi((geomunion(my_geom, j.wkb_geometry)));
         END LOOP;	
	 insert into pr_muni(municipio, wkb_geometry) values(i.municipio, my_geom);
         select into my_area area(my_geom);
         RAISE NOTICE 'table pr_muni updated, area is %', my_area; 
   END LOOP;
   Return;
END;
$$ LANGUAGE plpgsql;