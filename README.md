# CustomRegionTool

This tool facilitates writing to our Production MySQL database via a UI to accommodate custom regions (seemingly arbitrary groupings of properties), regardless of geographic location (as is customary). Mainly employs PHP on the back-end, with some JavaScript / Ajax on the front-end.

Tables involved are:

`custom_property_region_map`
`custom_region`
`custom_display_profile`
