<?php
//define('CONNECTION_PROXY', "your-proxy-address"); 

// --- bud tam bude include nebo exclude
$config=array(
    // --- Enduse potential INSPIRE
    "http://inspire.ec.europa.eu/codelist/EndusePotentialValue"=>array(
        "adapter"=> "inspireRegistry",
        //"include"=>array()
        "exclude"=>array(
            "http://inspire.ec.europa.eu/codelist/EndusePotentialValue/abrasiveMinerals"
        )
    ),
    // --- INSPIRE themes
    "http://inspire.ec.europa.eu/theme"=>array(
        "adapter"=> "inspireThemes"
    ),
    
    // --- Exploration activity type CGI
    "http://resource.geosciml.org/classifierscheme/cgi/201401/exploration-activity-type"=>array(
            "adapter" => "cgi",
            "url" => "http://resource.geosciml.org/sparql/cgi201211",
            "sparql" => "PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
                SELECT * WHERE {
                   ?id skos:inScheme <http://resource.geosciml.org/classifierscheme/cgi/201401/exploration-activity-type> .
                   ?id skos:prefLabel ?label .
                    { ?id skos:definition ?description .
                       ?id skos:broader ?broader .
                       ?broader skos:prefLabel ?broaderLabel .
                    }
                    UNION   {  
                        ?id skos:definition ?description .  
                        FILTER ( NOT EXISTS {?id skos:broader ?b .} ) 
                    } 
                }  "
    ),

    // --- GeoERA
    "https://data.geoscience.earth/ncl/geoera/keyword"=>array(
            "adapter" => "geoera",
            "url" => "https://data.geoscience.earth/ncl/system/query",
            "format" => "json",
            "nocache" => true,
            "sparql" => "PREFIX skos:<http://www.w3.org/2004/02/skos/core#>
                SELECT DISTINCT ?Concept ?prefLabel ?broader
                WHERE
                { ?Concept ?x skos:Concept .
                  ?Concept skos:broader ?broader .
                { ?Concept skos:prefLabel ?prefLabel . FILTER (regex(str(?prefLabel), '^$qstr.*', 'i')) }
                FILTER langMatches (lang(?prefLabel), '$lang')
                FILTER(regex(str(?Concept), 'ncl/geoera/'))
                } ORDER BY ?prefLabel LIMIT 50 OFFSET 0",
            "hierarchy" => "PREFIX skos:<http://www.w3.org/2004/02/skos/core#>
                SELECT ?hierarchy ?Concept ?prefLabel WHERE
                { {<$id> skos:broader ?Concept . ?Concept skos:prefLabel ?prefLabel . VALUES ?hierarchy {'b'} 
                FILTER langMatches (lang(?prefLabel), '$lang') }
                UNION { <$id> skos:narrower ?Concept . ?Concept skos:prefLabel ?prefLabel . VALUES ?hierarchy  {'n'}
                  FILTER langMatches (lang(?prefLabel), '$lang').
                }}",
                "translations" => "PREFIX skos:<http://www.w3.org/2004/02/skos/core#> 
                SELECT * WHERE { <$id> skos:prefLabel ?prefLabel}" 
    ),
    
    // --- GeoERA projects
    "http://resource.geolba.ac.at/egs" => array(
        "adapter" => "geoera",
            "url" => "http://resource.geolba.ac.at/PoolParty/sparql/egs",
            "format" => "application/json",
            "nocache" => true,
            "sparql" => "PREFIX skos:<http://www.w3.org/2004/02/skos/core#>
                SELECT DISTINCT ?Concept ?prefLabel ?broader
                WHERE
                { ?Concept ?x skos:Concept .
                <http://resource.geolba.ac.at/egs/16> skos:narrower+ ?Concept . 
                ?Concept skos:prefLabel ?projectcode .
                  ?Concept skos:broader ?broader .
                { ?Concept skos:prefLabel ?prefLabel . FILTER (regex(str(?prefLabel), '^$qstr.*', 'i')) }
                FILTER langMatches (lang(?prefLabel), '$lang')
                FILTER(regex(str(?Concept), '/egs/'))
                } ORDER BY ?prefLabel LIMIT 50 OFFSET 0",
            "hierarchy" => "PREFIX skos:<http://www.w3.org/2004/02/skos/core#> 
                SELECT ?hierarchy ?Concept ?prefLabel WHERE { {
                <$id> skos:broader ?Concept . ?Concept skos:prefLabel ?prefLabel . VALUES ?hierarchy {'b'} 
                FILTER langMatches (lang(?prefLabel), '$lang') } 
                UNION { <$id> skos:narrower ?Concept . ?Concept skos:prefLabel ?prefLabel . 
                VALUES ?hierarchy {'n'} FILTER langMatches (lang(?prefLabel), '$lang'). }}",
            "translations" => "PREFIX skos:<http://www.w3.org/2004/02/skos/core#> 
            SELECT * WHERE { <$id> skos:prefLabel ?prefLabel}"        
    ),


    // --- EU Countries 
    "https://publications.europa.eu/resource/authority/country"=>array(
        "adapter"=> "skos",
        "url" => "http://publications.europa.eu/mdr/resource/authority/country/skos/countries-skos.rdf"
    ),

    // ---- priorityDataset
    "http://inspire.ec.europa.eu/metadata-codelist/PriorityDataset"=>array(
        "adapter"=> "inspireRegistry"
    ),

    // ---- spatial data services
    "http://inspire.ec.europa.eu/metadata-codelist/SpatialDataServiceCategory"=>array(
        "adapter"=> "inspireRegistry"
    )
    
);