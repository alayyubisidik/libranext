# Libranext Design Guide

## 1. Design Direction

* Simple
* Clean
* Consistent
* Functional
* Responsive
* Light theme
* Blue as primary color

Design should prioritize usability over visual complexity.

## 2. UI Principles

* Keep layouts simple.
* Use consistent spacing and sizing.
* Reuse existing components.
* Avoid unnecessary decorative elements.
* Keep information easy to scan.
* Use clear labels and actions.
* Desktop-first but responsive on smaller screens.

## 3. Color

* Background: Light / White
* Primary: Blue
* Text: Dark / Neutral
* Border: Light gray
* Success: Green
* Warning: Yellow/Orange
* Error: Red

Use the primary blue consistently for important actions and active states.

## 4. Layout

Use a consistent application layout:

* Sidebar
* Topbar
* Main content area

Dashboard and management pages should follow the same layout structure.

## 5. Components

Prefer reusable components for:

* Buttons
* Forms
* Inputs
* Selects
* Tables
* Cards
* Badges
* Modals
* Alerts
* Pagination
* Empty states
* Loading states

Do not create multiple visual variations for the same purpose without a clear reason.

## 6. Forms

* Use clear labels.
* Group related fields.
* Show validation errors near the relevant field.
* Use consistent input sizes.
* Clearly distinguish primary and secondary actions.

## 7. Tables

Tables should:

* Be simple and readable.
* Have clear column names.
* Support pagination when needed.
* Provide actions consistently.
* Handle empty states.
* Be responsive where practical.

## 8. Feedback

Use clear feedback for user actions:

* Success
* Error
* Warning
* Confirmation

Destructive actions should require confirmation when appropriate.

## 9. Responsive Design

The application must work on:

* Desktop
* Tablet
* Mobile

Prioritize readability and usability rather than reproducing the desktop layout exactly on mobile.

## 10. Design Timing

Detailed page-level design is intentionally deferred.

First complete all application features and functionality.

After the features are complete:

1. Review all pages.
2. Establish consistent layouts.
3. Refine components.
4. Apply the final visual system.
5. Check responsive behavior.
6. Remove unnecessary visual complexity.

Do not spend significant effort on visual polish before the application functionality is complete.
