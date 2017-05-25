CREATE OR REPLACE FUNCTION range_cache() returns void as $$
DECLARE
  i record;
  j record;
  rec_int boolean;
BEGIN
  FOR i IN select ogc_fid, wkb_geometry from pr_wtshds LOOP
     FOR j IN select ogc_fid, wkb_geometry from pr_species_hex LOOP
        select into rec_int intersects(i.wkb_geometry, j.wkb_geometry);
        -- RAISE NOTICE 'range_ogc_fid %  counties_ogc_fid %  overlaps %', j.ogc_fid, i.ogc_fid, rec_int;
        if rec_int then
           insert into range_from_aoi(pr_species_hex_ogc_fid, pr_wtshds_ogc_fid) values (j.ogc_fid, i.ogc_fid);
        end if;
     END LOOP;
  END LOOP;
END;
$$ LANGUAGE plpgsql;
