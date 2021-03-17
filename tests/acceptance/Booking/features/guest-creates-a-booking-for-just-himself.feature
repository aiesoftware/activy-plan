Feature: Guests can make bookings for themselves and/or other other guests
  In order to take part in activities during my stay
  As a guest of the holiday park
  I want to make bookings for myself and additional guests

  Scenario: Johnny make a booking for just himself
    Given a Park named "Sunnyside"
    And the Park has a "Rock Climbing" Activity
    And the Activity has an Activity Slot at "2025-04-30 10:00" lasting "1" hour with a minimum age limit of "16" and "10" places available
    And "Johnny Rogers" is a "16" year old Guest staying at the "Sunnyside" Park between "2025-04-24 16:00" and "2025-05-01 10:00"
    When he makes a Booking for himself on that Activity Slot
    Then the Booking should be added to his Planner
    And that Activity Slot should have "9" places remaining

  Scenario: Johnny attempts to make a booking for an Activity Slot that has insufficient places available
    Given a Park named "Sunnyside"
    And the Park has a "Rock Climbing" Activity
    And the Activity has an Activity Slot at "2025-04-30 10:00" lasting "1" hour with a minimum age limit of "16" and "0" places available
    And "Johnny Rogers" is a "16" year old Guest staying at the "Sunnyside" Park between "2025-04-24 16:00" and "2025-05-01 10:00"
    When he attempts to make a Booking on that Activity Slot
    Then that Activity Slot should have "0" places remaining
    And the Booking should not be added to his Planner

  Scenario: Johnny attempts to create a booking for an Activity he is too young for
    Given a Park named "Sunnyside"
    And the Park has a "Scuba Diving" Activity
    And the Activity has an Activity Slot at "2025-04-30 10:00" lasting "1" hour with a minimum age limit of "18" and "10" places available
    And "Johnny Rogers" is a "17" year old Guest staying at the "Sunnyside" Park between "2025-04-24 16:00" and "2025-05-01 10:00"
    When he attempts to make a Booking on that Activity Slot
    Then that Activity Slot should have "10" places remaining
    And the Booking should not be added to his Planner

  Scenario: Johnny attempts to make a booking for an Activity Slot that is after his checkout day
    Given a Park named "Sunnyside"
    And the Park has a "Scuba Diving" Activity
    And the Activity has an Activity Slot at "2025-04-30 10:00" lasting "1" hour with a minimum age limit of "18" and "10" places available
    And "Johnny Rogers" is a "18" year old Guest staying at the "Sunnyside" Park between "2025-04-22 16:00" and "2025-04-29 10:00"
    When he attempts to make a Booking on that Activity Slot
    Then that Activity Slot should have "10" places remaining
    And the Booking should not be added to his Planner

  Scenario: Johnny attempts to make a booking for an Activity Slot that is before his arrival time
    Given a Park named "Sunnyside"
    And the Park has a "Scuba Diving" Activity
    And the Activity has an Activity Slot at "2025-04-22 15:50" lasting "1" hour with a minimum age limit of "18" and "10" places available
    And "Johnny Rogers" is a "18" year old Guest staying at the "Sunnyside" Park between "2025-04-22 16:00" and "2025-04-29 10:00"
    When he attempts to make a Booking on that Activity Slot
    Then that Activity Slot should have "10" places remaining
    And the Booking should not be added to his Planner

  Scenario: Johnny makes a booking for an Activity Slot that is on the same day that he checks out on
    Given a Park named "Sunnyside"
    And the Park has a "Scuba Diving" Activity
    And the Activity has an Activity Slot at "2025-04-29 19:00" lasting "1" hour with a minimum age limit of "18" and "10" places available
    And "Johnny Rogers" is a "18" year old Guest staying at the "Sunnyside" Park between "2025-04-22 16:00" and "2025-04-29 10:00"
    When he makes a Booking for himself on that Activity Slot
    Then that Activity Slot should have "9" places remaining
    And the Booking should be added to his Planner
#
#  Scenario: Johnny attempts to create a booking for an Activity Slot that is at a different Park
#    Given an Activity at the "Sunnyside" Park named "Scuba Diving" with a minimum age limit of "18" has an Activity Slot at "2025-04-30 10:00" lasting "1 hour" with "10" places available
#    And "Johnny Rogers" is a "18" year old Guest staying at the "Bayside" Park between "2025-04-24 16:00" and "2025-05-01 10:00"
#    When he attempts to create a Booking on that Activity Slot that is outside of his stay dates
#    Then the Activity Slot should have "10" places remaining
#    And the Booking should not be added to his Planner
