## Process
1. **Examine reference files** above to understand the pattern
2. **Find best matching HTML** from `/frontend/build/` for the sales page
3. **Restructure target blade file** to match HTML classes/layout exactly
4. **Preserve ALL backend** - PHP logic, forms, i18n, auth, variables
5. **Use**: `@extends('layouts.taskly-modern')`

## Rules
- **NO CSS additions** - only restructure to match layout/classes
- **Read files completely** when examining
- **Match HTML structure exactly** for automatic CSS compatibility  
- **Keep all backend functionality intact**

## Goal
Sales page restructured to frontend/build styling with working backend functionality.
