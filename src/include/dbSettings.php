<?php

$privileges = array(
  "edit"  => array("user", "admin"),
  "admin" => array("admin")
);

$groups = array(
  "ID_DESCRIPTION" => array(
    "name" => "ID/Description"
  ),
  "LONGTEXT" => array(
    "name" => "Text Description"
  ),
  "TARGET_ETC" => array(
    "name" => "Target/Mission/Host/Instrument"
  ),
  "DEVELOPMENT" => array(
    "name" => "Development/Review"
  ),
  "ARCHIVING" => array(
    "name" => "Archiving/Delivery"
  ),
);

$storageUnitsPattern = "/^\\s*([^\\s]*[0-9])\\s*([A-Za-z]*)?\\s*/";

$storageUnits = array(
  ""    => 1,
  "b"   => 1,
  "k"   => 1024,
  "kb"  => 1024,
  "kib" => 1024,
  "m"   => pow(1024, 2),
  "mb"  => pow(1024, 2),
  "mib" => pow(1024, 2),
  "g"   => pow(1024, 3),
  "gb"  => pow(1024, 3),
  "gib" => pow(1024, 3),
  "t"   => pow(1024, 4),
  "tb"  => pow(1024, 4),
  "tib" => pow(1024, 4)
);

$viewOrder = array(
  "DATA_SET_ID",
  "ACTIVE_FLAG",
  "DISCREPANCY_FLAG",
  "REPORT_TO_EN",
  "MIGRATED_FLAG",
  "VOLUME_ID",
  "DATA_SET_NAME",
  #   "WEBSITE_DS_NAME",
  "COLLOQUIAL_ID",
  "SBN_RESPONSIBLE_PARTY",
  "SUBNODE_ID",
  "DATA_PROVIDER",
  "OBSERVATION_TYPE",
  "SUPERSEDED_DS_ID",
  "NSSDC_ID",
  "SIP_ID",
  "ADS_ID",
  "DOI",
  "LOCATION",
  "SIZE",
  "CREATE_DTS",
  "EDIT_DTS",
  "TARGET_NAME",
  "TARGET_TYPE",
  #   "MISSION_ID",
  "MISSION_NAME",
  "HOST_NAME",
  "INSTRUMENT_NAME",
  "DEVELOPMENT_STATUS",
  "ARCHIVE_STATUS",
  "DATA_SET_TYPE",
  "OLAF_DELIVERY_FLAG",
  "DRAFT_DATA_RECEIVED_DATE_FIRST",
  "DRAFT_DATA_RECEIVED_DATE",
  #   "DRAFT_DATA_ACCEPTED_DATE",
  "DRAFT_DATA_POSTED_DATE",
  "REVIEW_DATE",
  "REVIEW_RESULT",
  "CERTIFIED_FLAG",
  "FERRET_STATUS",
  "FERRET_COMMENT",
  "FERRET_INGEST_DATE",
  "MIGRATION_STATUS",
  "MIGRATION_TO",
  "MIGRATION_DATE",
  "MIGRATION_COMMENT",
  #   "PROFILE_SERVER_INGEST_DATE",
  "CATALOG_FILES_TO_EN_DATE",
  #   "REVIEW_EN_INGEST_DATE",
  "CATALOG_FILES_TO_NSSDC_DATE",
  "NSSDC_ID_RECEIVED_DATE",
  "FINAL_DATA_RECEIVED_DATE",
  #   "FINAL_DATA_ACCEPTED_DATE",
  "FINAL_DATA_POSTED_DATE",
  "ABSTRACT_TO_ADS_DATE",
  "RELEASE_DATE_FIRST",
  "RELEASE_DATE",
  #   "HARDCOPY_MADE_DATE",
  #   "LAST_HARD_COPY_REFRESH_DATE",
  #   "REVISED_CATALOG_FILES_TO_EN_DATE",
  "FINAL_EN_INGEST_DATE",
  "NSSDC_UPLOAD_DATE",
  "NSSDC_ACCEPTED_DATE",
  "ABSTRACT",
  "CITATION_DESC",
);

$outputOrder = array(
  "DATA_SET_ID",
  "ACTIVE_FLAG",
  "DISCREPANCY_FLAG",
  "REPORT_TO_EN",
  "MIGRATED_FLAG",
  "VOLUME_ID",
  "DATA_SET_NAME",
  #   "WEBSITE_DS_NAME",
  "COLLOQUIAL_ID",
  "SBN_RESPONSIBLE_PARTY",
  "SUBNODE_ID",
  "DATA_PROVIDER",
  "OBSERVATION_TYPE",
  "SUPERSEDED_DS_ID",
  "NSSDC_ID",
  "SIP_ID",
  "ADS_ID",
  "DOI",
  "LOCATION",
  "SIZE",
  "CREATE_DTS",
  "EDIT_DTS",
  "TARGET_NAME",
  "TARGET_TYPE",
  #   "MISSION_ID",
  "MISSION_NAME",
  "HOST_NAME",
  "INSTRUMENT_NAME",
  "DEVELOPMENT_STATUS",
  "ARCHIVE_STATUS",
  "DATA_SET_TYPE",
  "OLAF_DELIVERY_FLAG",
  "DRAFT_DATA_RECEIVED_DATE_FIRST",
  "DRAFT_DATA_RECEIVED_DATE",
  #   "DRAFT_DATA_ACCEPTED_DATE",
  "DRAFT_DATA_POSTED_DATE",
  "REVIEW_DATE",
  "REVIEW_RESULT",
  "CERTIFIED_FLAG",
  "FERRET_STATUS",
  "FERRET_INGEST_DATE",
  "MIGRATION_STATUS",
  "MIGRATION_TO",
  "MIGRATION_DATE",
  #   "PROFILE_SERVER_INGEST_DATE",
  "CATALOG_FILES_TO_EN_DATE",
  #   "REVIEW_EN_INGEST_DATE",
  "CATALOG_FILES_TO_NSSDC_DATE",
  "NSSDC_ID_RECEIVED_DATE",
  "FINAL_DATA_RECEIVED_DATE",
  #   "FINAL_DATA_ACCEPTED_DATE",
  "FINAL_DATA_POSTED_DATE",
  "ABSTRACT_TO_ADS_DATE",
  "RELEASE_DATE_FIRST",
  "RELEASE_DATE",
  #   "HARDCOPY_MADE_DATE",
  #   "LAST_HARD_COPY_REFRESH_DATE",
  #   "REVISED_CATALOG_FILES_TO_EN_DATE",
  "FINAL_EN_INGEST_DATE",
  "NSSDC_UPLOAD_DATE",
  "NSSDC_ACCEPTED_DATE",
  /* Show the long text fields at the end */
  "ABSTRACT",
  "CITATION_DESC",
  "FERRET_COMMENT",
  "MIGRATION_COMMENT",
);

$passThrough = array(
  "date"     => array("-show", "-min", "-max", "-null"),
  "number"   => array("-show", "-min", "-max"),
  "flag"     => array("-show", "-flag"),
  "set"      => array("-show", "-select[]", "-nonstandard"),
  "text"     => array("-show", "-text", "-null", "-partial"),
  "longtext" => array("-show", "-text", "-null", "-partial"),
  "object"   => array("-show", "-text", "-null", "-partial", "-select[]"),
);

$keywordInfo = array(

  "UUID"        => array(
    "displayName" => "uuid",
    "type"        => "debug",
    "group"       => "DEBUG"
  ),

  "ABSTRACT"    => array(
    "displayName" => "Abstract",
    "type"        => "longtext",
    "description" => "This is the abstract of the data set.  Usually this is the ABSTRACT_DESC found in the data set catalog file.",
    "group"       => "LONGTEXT"
  ),

  "ABSTRACT_TO_ADS_DATE" => array(
    "displayName" => "Abstract to ADS Date",
    "type"        => "date",
    "description" => "The most recent date that the data set abstracts were sent to the ADS.",
    "group"       => "ARCHIVING"
  ),

  "ACTIVE_FLAG" => array(
    "displayName" => "Active",
    "type"        => "flag",
    "description" => "Specifies if the data set is still being developed or awaiting any action.",
    "group"       => "ID_DESCRIPTION",
    "letter"      => "A",
    "positive"    => "Active",
    "negative"    => "Not Active"
  ),

  "ADS_ID" => array(
    "displayName" => "ADS ID",
    "type"        => "text",
    "description" => "The ID for the abstract of this data set in ADS.  See Abstract to ADS date for the date the abstract was submitted into ADS, if it was.",
    "group"       => "ID_DESCRIPTION"
  ),

  "ARCHIVE_STATUS" => array(
    "displayName" => "Archive Status",
    "type"        => "set",
    "description" => "The PDS3 ARCHIVE_STATUS for the data set. For PDS4 bundles/collections this keyword is used for internal purposes only since PDS4 does not have an archive status.  The value 'See Notes' means the development ceased before the data set reached any archivable state.",
    "group"       => "ARCHIVING",
    "legalValues" => array(
      "IN QUEUE",
      "PRE PEER REVIEW",
      "IN PEER REVIEW",
      "IN LIEN RESOLUTION",
      "LOCALLY ARCHIVED",
      "ARCHIVED",
      "SUPERSEDED",
      "SAFED",
      "see notes"
    )
  ),

  "CATALOG_FILES_TO_EN_DATE" => array(
    "displayName" => "Catalog Files to EN Date",
    "type"        => "date",
    "description" => "The most recent date that the data set's catalog files were sent to EN.",
    "group"       => "DEVELOPMENT"
  ),

  "CATALOG_FILES_TO_NSSDC_DATE" => array(
    "displayName" => "NSSDC ID Requested Date",
    "type"        => "date",
    "description" => "The most recent date that an NSSDC ID was requested from the NSSDC.  This is done thru the NSSDC PSI (PDS Submission Interface).",
    "group"       => "DEVELOPMENT"
  ),

  "CERTIFIED_FLAG" => array(
    "displayName" => "Certification Status",
    "type"        => "set",
    "description" => "The certification status of the data set.",
    "group"       => "DEVELOPMENT",
    "legalValues" => array(
      "CERTIFIED",
      "NOT CERTIFIED"
    )
  ),

  "CITATION_DESC" => array(
    "displayName" => "Citation Description",
    "type"        => "longtext",
    "description" => "This is the citation description of the data set.  Usually this is the CITATION_DESC found in the data set catalog file.",
    "group"       => "LONGTEXT"
  ),

  "COLLOQUIAL_ID" => array(
    "displayName" => "Colloquial ID",
    "type"        => "text",
    "description" => "Locally used short alias for the data set, if any.",
    "group"       => "ID_DESCRIPTION"
  ),

  "CREATE_DTS" => array(
    "displayName" => "DB creation date",
    "type"        => "date",
    "description" => "Auto generated date of when the data set was first entered into the DSTracker.",
    "group"       => "ID_DESCRIPTOIN"
  ),

  "DATA_PROVIDER" => array(
    "displayName" => "Data Provider",
    "type"        => "text",
    "description" => "Provider of data set.",
    "group"       => "ID_DESCRIPTION"
  ),

  "DATA_SET_ID" => array(
    "displayName" => "Dataset ID",
    "type"        => "text",
    "description" => "PDS3 (or earlier) DATA_SET_ID of the data set or PDS4 Bundle/Collection LIDVID.",
    "group"       => "ID_DESCRIPTION"
  ),

  "DATA_SET_NAME" => array(
    "displayName" => "Data Set Display Name",
    "type"        => "text",
    "description" => "A meaningful and descriptive name or title of the data set.  May use the DATA_SET_NAME found in the data set catalog file if it is sufficient.  Used for website generation.",
    "group"       => "ID_DESCRIPTION"
  ),

  "DATA_SET_TYPE" => array(
    "displayName" => "Dataset Type",
    "type"        => "set",
    "description" => "Whether this data set is a PDS3 (accumulating or versioned) data set, PDS4 bundle (using collection LIDs or LIDVIDs), or PDS4 collection.",
    "group"       => "ARCHIVING",
    "legalValues" => array(
      "PDS3 ACCUMULATING",
      "PDS3 VERSIONED",
      "PDS4 Bundle (LIDs)",
      "PDS4 Bundle (LIDVIDs)",
      "PDS4 Collection"
    )
  ),

  "DEVELOPMENT_STATUS" => array(
    "displayName" => "Development Status",
    "type"        => "set",
    "description" => "The in-house development status for the data set.  Valid values:<ul>
				<li>'planned' - The data set is planned for development.</li>
				<li>'in prep' - The pre-reviewed data set is being developed.</li>
				<li>'peer rev' - The data set is ready for or currently in peer review.</li>
				<li>'lien resolution' - The data set is being lien resolved.</li>
				<li>'final post' - The lien resolved data set has been publicly posted.</li>
				<li>'EN ingested' - The catalog files for the lien resolved, posted data set has been ingested at EN.</li>
				<li>'NSSDC deposit' - The lien resolved, posted data set has been archived at the NSSDC and is EN ingested.  This is the usual final state of a data set, implying no further work will be done on it.</li>
				<li>'withdrawn' - The data set has been withdrawn and will not be hosted/maintained anymore.  This is a final state of a data set, implying no further work will be done on it.</li>
				<li>'rejected' - The post-review data set has been rejected and will not be hosted/maintained anymore.  This is a final state of a data set, implying no further work will be done on it.</li>
				<li>'SBN local' - The data set is a local non-archivable product created for the convenience of users.  This is a final state of a data set, implying no further work will be done on it.</li></ul>",
    "group"       => "DEVELOPMENT",
    "legalValues" => array(
      "planned",
      "in prep",
      #			     "internal rev",
      #			     "online",
      #			     "delivered",
      "peer rev",
      "lien resolution",
      "liens resolved",
      "final post",
      "EN ingested",
      "NSSDC deposit",
      "withdrawn",
      "rejected",
      "SBN local"
    )
  ),

  "DISCREPANCY_FLAG" => array(
    "displayName" => "PDS Non-Compliant",
    "type"        => "flag",
    "description" => "Specifies that the data set is NOT PDS compliant and there is no intention of fixing it.",
    "group"       => "ID_DESCRIPTION",
    "letter"      => "D",
    "positive"    => "Non-Compliant",
    "negative"    => "Compliant"
  ),

  #   "DRAFT_DATA_ACCEPTED_DATE" => array(
  #      "displayName" => "Draft Data Accepted Date",
  #      "type"        => "date",
  #      "group"       => "DEVELOPMENT"),

  "DOI" => array(
    "displayName" => "DOI",
    "type"        => "text",
    "description" => "The Digital Object Identifier (DOI) citation number for this data set entry (if any).",
    "group"       => "ID_DESCRIPTION"
  ),

  "DRAFT_DATA_POSTED_DATE" => array(
    "displayName" => "Draft Data Posted Date",
    "type"        => "date",
    "description" => "The most recent date the draft data for the data set was publicly posted.",
    "group"       => "DEVELOPMENT"
  ),

  "DRAFT_DATA_RECEIVED_DATE" => array(
    "displayName" => "Draft Data Most Recent Delivery Date",
    "type"        => "date",
    "description" => "The most recent date the draft data for the data set delivered.",
    "group"       => "DEVELOPMENT"
  ),

  "DRAFT_DATA_RECEIVED_DATE_FIRST" => array(
    "displayName" => "Draft Data First Delivery Date",
    "type"        => "date",
    "description" => "Date the draft data for the data set was delivered for the first time.",
    "group"       => "DEVELOPMENT"
  ),

  "EDIT_DTS" => array(
    "displayName" => "DB modify date",
    "type"        => "date",
    "description" => "An auto generated date of when the last time this data set entry was updated in the DSTracker.  Note this applies only to fields in the DATASET table.",
    "group"       => "ID_DESCRIPTOIN"
  ),

  "FERRET_COMMENT" => array(
    "displayName" => "Ferret Comment",
    "type"        => "longtext",
    "description" => "Describes why a data set is not in Ferret or what needs to be done to get it into Ferret.",
    "group"       => "DEVELOPMENT"
  ),

  "FERRET_INGEST_DATE" => array(
    "displayName" => "Ferret Ingest Date",
    "type"        => "date",
    "description" => "The date this version of the data set was first ingested into the Data Ferret.",
    "group"       => "DEVELOPMENT"
  ),

  "FERRET_STATUS" => array(
    "displayName" => "Ferret Status",
    "type"        => "set",
    "description" => "The status of the data set for Ferret ingestion.  Valid ordered values:<ul>
			<li>'Not Decided' - It is not yet decided whether this data set should be loaded into the ferret or not. (Default/Initial value)</li>
			<li>'Ready to Load' - The data set is ready to be loaded into the ferret, and as far as we know, no changes need to be made to the data set or to the ferret to enable loading.</li>
			<li>'Loading Problem' - This version of this data set should go into the ferret but has not been successfully loaded yet.  It may be that something needs to be fixed either in the data set or in the ferret before it can be loaded.  This status should be accompanied by a comment (see the Ferrett Comment field) explaining what needs to be done.</li>
			<li>'Loaded' - This version of this data set is in the ferret now.</li>
			<li>'Superseded' - This version of this data set has been superseded in the ferret by a later version or by a different data set.  This applies even to older versions of data sets from before we had the ferret, as long as a more recent version of their data is currently in the ferret.  Note that a data set version can have an archive status of superseded and not yet be superseded in the ferret.</li>
			<li>'Never' - We intend that this data set will never go into the ferret.  This status should be accompanied by a comment (see the Ferrett Comment field) explaining why it is not to go into the ferret.</li></ul>",
    "group"       => "DEVELOPMENT",
    "legalValues" => array(
      "Not Decided",
      "Ready to Load",
      "Loading Problem",
      "Loaded",
      "Superseded",
      "Never"
    )
  ),

  /*   "FINAL_DATA_ACCEPTED_DATE" => array(
      "displayName" => "Final Data Accepted Date",
      "type"        => "date",
      "description" => "<strong>To be removed.</strong>",
      "group"       => "ARCHIVING"),
*/
  "FINAL_DATA_POSTED_DATE" => array(
    "displayName" => "Final Data Posted Date",
    "type"        => "date",
    "description" => "The date that the lien resolved version of the data set was posted online.  This field is to be used in conjunction with the development status 'final post'.",
    "group"       => "ARCHIVING"
  ),

  "FINAL_DATA_RECEIVED_DATE" => array(
    "displayName" => "Final Data Received Date",
    "type"        => "date",
    "description" => "The most recent date that the final version of the data set was received.",
    "group"       => "ARCHIVING"
  ),

  "FINAL_EN_INGEST_DATE" => array(
    "displayName" => "Final EN Ingest Date",
    "type"        => "date",
    "description" => "The date the final copy of the catalog files for the data set were ingested at the EN.  This field is used in conjunction with the development status 'EN ingested'.",
    "group"       => "ARCHIVING"
  ),

  #   "HARDCOPY_MADE_DATE" => array(
  #      "displayName" => "Hardcopy Made Date",
  #      "type"        => "date",
  #      "group"       => "ARCHIVING"),

  "HOST_NAME" => array(
    "displayName" => "Host Name",
    "type"        => "object",
    "description" => "A list (one per field entry) of the instrument host names for the data set.  Usually this is the INSTRUMENT_HOST_NAME found in the instrument host catalog file.",
    "group"       => "TARGET_ETC",
    "dbTable"     => "HOST"
  ),

  "INSTRUMENT_NAME" => array(
    "displayName" => "Instrument Name",
    "type"        => "object",
    "description" => "A list (one per field entry) of the instrument names for the data set.  Usually this is the INSTRUMENT_NAME found in the instrument catalog files.",
    "group"       => "TARGET_ETC",
    "dbTable"     => "INSTRUMENT"
  ),

  #   "LAST_HARD_COPY_REFRESH_DATE" => array(
  #      "displayName" => "Last Hardcopy Refresh Date",
  #      "type"        => "date",
  #      "group"       => "ARCHIVING"),

  "LOCATION" => array(
    "displayName" => "Location",
    "type"        => "text",
    "description" => "The URL (if publicly posted) or local disk reference (if in development or private) of the data set's location.  A URL assumes the data set is public.",
    "group"       => "ID_DESCRIPTION"
  ),

  #   "MISSION_ID" => array(
  #      "displayName" => "Mission ID",
  #      "type"        => "object",
  #      "description" => "<strong>Please remove and combine with Mission Name.</strong>",
  #      "group"       => "TARGET_ETC",
  #      "dbTable"     => "MISSION"),

  "MIGRATED_FLAG" => array(
    "displayName" => "Migrated Data Set",
    "type"        => "flag",
    "description" => "Whether or not the data set was the result of migrating other data set(s).",
    "group"       => "ID_DESCRIPTION",
    "letter"      => "M",
    "positive"    => "Result of Migration",
    "negative"    => "Not Result of Migration"
  ),

  "MIGRATION_COMMENT" => array(
    "displayName" => "Migration Comment",
    "type"        => "longtext",
    "description" => "Open text field containing additional comments regarding the migration process (problems, why to not migrate, migrated from details if complicated, etc).",
    "group"       => "DEVELOPMENT"
  ),

  "MIGRATION_DATE" => array(
    "displayName" => "Migration Date",
    "type"        => "date",
    "description" => "The date this version of the data set was last migrated.",
    "group"       => "DEVELOPMENT"
  ),

  "MIGRATION_STATUS" => array(
    "displayName" => "Migration Status",
    "type"        => "set",
    "description" => "The in-house development status for the data set.  Valid values:<ul>
				<li>'Not Decided' - It is not yet decided whether this data set version should be migrated to a new PDS version or not. (Default/Initial value).</li>
				<li>'Current' - This data set version is using the latest PDS version (PDS4 currently).</li>
				<li>'To be Migrated to PDS4' - This data set version has been marked to be migrated to PDS4..</li>
				<li>'Problem Migrating' - This data set version should be migrated but there is problem holding it back.  See Migration Comment for details.</li>
				<li>'Migrated to PDS4' - This data set version has been successfully migrated to PDS4.</li>
				<li>'Do Not Migrate' - This data set version should not be migrated to a newer PDS version..</li></ul>",
    "group"       => "DEVELOPMENT",
    "legalValues" => array(
      "Not Decided",
      "Current",
      "To be Migrated to PDS4",
      "Problem Migrating",
      "Migrated to PDS4",
      "Do Not Migrate"
    )
  ),

  "MIGRATION_TO" => array(
    "displayName" => "Migration to Data Set ID",
    "type"        => "text",
    "description" => "Specifies the id of the data set that this data set is being migrated to in the archive, if any.  Currenlty this can be either one or more PDS4 bundle(s) or PDS4 collection(s).",
    "group"       => "DEVELOPMENT",
  ),

  "MISSION_NAME" => array(
    "displayName" => "Mission Name",
    "type"        => "object",
    "description" => "Is a list (one per field entry) of the mission names associated with the data set.  This field should contain the Mission Name followed by the acronym of the mission name in parentheses, example: 'Deep Impact (DI)'.  This information is usually found in the mission catalog file(s).",
    "example"     => "'Deep Impact (DI)'",
    "group"       => "TARGET_ETC",
    "dbTable"     => "MISSION"
  ),

  "NSSDC_ACCEPTED_DATE" => array(
    "displayName" => "NSSDC Accepted Date",
    "type"        => "date",
    "description" => "The date that the NSSDC confirmed the success of the deep archiving of the data set and receipt by SBN of the SIP ID.  This field is used in conjunction with the development status 'NSSDC deposit'.",
    "group"       => "ARCHIVING"
  ),

  "NSSDC_ID" => array(
    "displayName" => "NSSDC ID",
    "type"        => "text",
    "description" => "The ID received from the NSSDC for a particular data set/volume(s).  This is received prior to submitting for deep archive.",
    "group"       => "ID_DESCRIPTION"
  ),

  "NSSDC_ID_RECEIVED_DATE" => array(
    "displayName" => "NSSDC ID Received Date",
    "type"        => "date",
    "description" => "The most recent date that the NSSDC ID was received from the NSSDC.",
    "group"       => "DEVELOPMENT"
  ),

  "NSSDC_UPLOAD_DATE" => array(
    "displayName" => "Data sent to NSSDC Date",
    "type"        => "date",
    "description" => "The date that the data set was sent or posted for upload to the NSSDC deep archiving.",
    "group"       => "ARCHIVING"
  ),

  #   "PROFILE_SERVER_INGEST_DATE" => array(
  #      "displayName" => "Profile Server Ingest Date",
  #      "type"        => "date",
  #      "group"       => "DEVELOPMENT"),

  "OBSERVATION_TYPE" => array(
    "displayName" => "Observation Type",
    "type"        => "text",
    "description" => "The observation/data types for the data set specifically used for website generation.",
    "group"       => "ID_DESCRIPTION"
  ),

  "OLAF_DELIVERY_FLAG" => array(
    "displayName" => "OLAF Delivery",
    #      "type"        => "flag",
    #      "group"       => "DEVELOPMENT",
    #      "letter"      => "O",
    #      "positive"    => "OLAF Delivery",
    #      "negative"    => "No OLAF Delivery"),
    "type"        => "set",
    "description" => "States whether or not this data set was generated by OLAF.  Valid values:<ul>
			<li>'full' - The data set was generated by OLAF and not subsequently modified before archiving.</li>
			<li>'partial' - The data set was generated by OLAF but was subsequently modified before archiving.</li>
			<li>'no' - The data set was not generated by OLAF.</li></ul>",
    "group"       => "DEVELOPMENT",
    "legalValues" => array(
      "full",
      "partial",
      "no"
    )
  ),

  "RELEASE_DATE" => array(
    "displayName" => "Most Recent Release Date",
    "type"        => "date",
    "description" => "The most recent date that the data set was released to the public.",
    "group"       => "ARCHIVING"
  ),

  "RELEASE_DATE_FIRST" => array(
    "displayName" => "First Release Date",
    "type"        => "date",
    "description" => "The first date that this data set was released to the public (with any archive status).",
    "group"       => "ARCHIVING"
  ),

  "REPORT_TO_EN" => array(
    "displayName" => "Report to EN",
    "type"        => "flag",
    "description" => "Whether or not the Engineering Node should know about this data set.",
    "group"       => "ID_DESCRIPTION",
    "letter"      => "R",
    "positive"    => "Report to EN",
    "negative"    => "Don't report to EN"
  ),

  "REVIEW_DATE" => array(
    "displayName" => "Review Date",
    "type"        => "date",
    "description" => "The review date for the data set.  If this data set has been or will be reviewed multiple times, the date of the most recent review should be put here.",
    "group"       => "DEVELOPMENT"
  ),

  /*   "REVIEW_EN_INGEST_DATE" => array(
      "displayName" => "Review EN Ingest Date",
      "type"        => "date",
      "description" => "<strong>To be removed.</strong>",
      "group"       => "DEVELOPMENT"),
*/
  "REVIEW_RESULT" => array(
    "displayName" => "Review Result",
    "type"        => "set",
    "description" => "The result of the Peer Review for the data set.",
    "group"       => "DEVELOPMENT",
    "legalValues" => array(
      "Accepted with no liens. Ready to be archived.",
      "Certified. Has minor liens.",
      "Not Certified. Delta review required.",
      "Rejected."
    )
  ),

  #   "REVISED_CATALOG_FILES_TO_EN_DATE" => array(
  #      "displayName" => "Revised Catalog Files to EN Date",
  #      "type"        => "date",
  #      "group"       => "ARCHIVING"),

  "SIP_ID" => array(
    "displayName" => "SIP ID",
    "type"        => "text",
    "description" => "The unique delivery ID received from the NSSDC after a data set/volume has been archived in the deep archive.",
    "group"       => "ID_DESCRIPTION"
  ),

  "SIZE" => array(
    "displayName" => "Size",
    "type"        => "number",
    "description" => "The size (in bytes) the archivable part of the data set takes up on hard disk.",
    "group"       => "ID_DESCRIPTION",
    "units"       => "storage",
    "hint"        => "e.g. 1kb, 2 MB, 512 b",
    "example"     => "1kb, 2 MB, 512 b"
  ),

  "SBN_RESPONSIBLE_PARTY" => array(
    "displayName" => "SBN Responsible Party",
    "type"        => "text",
    "description" => "The responsible SBN party to contact about this data set.",
    "group"       => "ID_DESCRIPTION"
  ),

  "SUBNODE_ID" => array(
    "displayName" => "Node/Subnode ID",
    "type"        => "set",
    "description" => "The Node/Sub-node ID hosting the data sets.",
    "group"       => "ID_DESCRIPTION",
    "legalValues" => array(
      "SBN-PSI",
      "SBN-UMD"
    )
  ),

  "SUPERSEDED_DS_ID" => array(
    "displayName" => "Superseded Data Set ID",
    "type"        => "text",
    "description" => "Specifies the data set id of the data set that this data set is superseding in the archive, if any.",
    "group"       => "ID_DESCRIPTION",
  ),

  "TARGET_NAME" => array(
    "displayName" => "Target Name",
    "type"        => "object",
    "description" => "Is a list (one per field entry) of target names for targets found in the data set.  Usually this is all of the TARGET_NAME entries found in the data set catalog file.  Each target name should use the proper designation/identification of the object.  This field should not include lists of targets from a survey or table.  In those cases, 'ASTEROID' or 'COMET' or something similar is sufficient.",
    "group"       => "TARGET_ETC",
    "dbTable"     => "TARGET"
  ),

  "TARGET_TYPE" => array(
    "displayName" => "Target Type",
    "type"        => "object",
    "description" => "Is a list of target types, one paired with each Target Name.  For multiple types for a single target, create one Target Name/Type pair for each type, which may produce duplicate Target Names.  Valid values: 'Asteroid', 'Comet', 'Dust', 'Planet', 'Satellite', 'Lab Sample', 'Other'",
    "group"       => "TARGET_ETC",
    "dbTable"     => "TARGET"
  ),

  "VOLUME_ID" => array(
    "displayName" => "Volume ID and Version",
    "type"        => "text",
    "description" => "Combinations of PDS VOLUME_ID and VOLUME_VERSION_ID of the data set found in the volume catalog file.  Ranges are acceptable.",
    "example"     => "&#34;HAL_1001-2 VERSION 1, IHWORG_0066 VERSION 1&#34;",
    "group"       => "ID_DESCRIPTION"
  ),

  #   "WEBSITE_DS_NAME" => array(
  #      "displayName" => "Website Display Name",
  #      "type"        => "text",
  #      "group"       => "ID_DESCRIPTION"),

);
