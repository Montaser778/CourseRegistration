-- Student General Information
select * from students s, plans p, plansdtl pp, `rules` r
where s.`StudentID` = '2249001003'
and s.`PlanNo` = p.`PlanNo`
and p.`PlanNo` = pp.`PlanNo`
and s.`RegionID` = pp.`RegionID`
and p.`RuleNo` = r.`RuleNo`;

-- Temporarely Registered Courses for Current Semester
select * from students s, preregistration pr, plandescription pd, courses c
where s.`StudentID` = '2249001003'
and s.`StudentID` = pr.`StudentID`
and pr.`PlanDescNo` = pd.`PlanDescNo`
and pd.`CourseNo` = c.`CourseNo`;

-- Temporarely Registered Courses for Current Semester - Refined Result
SELECT pr.`AdviserApp`, pr.`AccountApp`, pr.`RegApp`, pr.`CourseStatus`, pr.`CourseSymbol`, c.`CourseNameAra` 
FROM students s, ptc.`preregistration` pr, plandescription pd, courses c
WHERE s.`StudentID` = '2249001003'
and s.`StudentID` = pr.`StudentID`
and pr.`PlanDescNo` = pd.`PlanDescNo`
and pd.`CourseNo` = c.`CourseNo`
and pr.`CourseStatus` < 3 
AND pr.`Transferred` = 0
AND pr.`PreRegID` = (
          SELECT MAX(prxa.`PreRegID`)
          FROM ptc.`preregistration` prxa
          WHERE prxa.`StudentID` = pr.`StudentID`
          AND prxa.`PlanDescNo` = pr.`PlanDescNo`
);

-- Registered Courses So far
select * from students s, `academiccalendar` ac, registration reg, plandescription pd, courses c
where s.`StudentID` = '2249001003'
and s.`StudentID` = reg.`StudentID`
and ac.`CalendarNo` = 46
and reg.`PlanDescNo` = pd.`PlanDescNo`
and pd.`CourseNo` = c.`CourseNo`;

-- Offered courses for a specific student during current semester (calendar = 52)
select * from adviser.`students`s, adviser.`offeredcourses` oc, adviser.`plandescription` pd, adviser.`courses` c
where s.`StudentID` = '2249001003'
and s.`PlanNo` = oc.`PlanNo`
and oc.`CalendarNo` = 52
and oc.`PlanDescNo` = pd.`PlanDescNo`
and pd.`CourseNo` = c.`CourseNo`;

-- Calculates Accumulated For a given semseter, from admitted level to admitted level
call adviser.`CalculateStudentCumul`('2245141010', 47, 1, 4);
-- Same but as a function
select `CalculateStudentCumul`('2245141010', 47, 1, 4);

-- Calculates GPA For a given semseter, from admitted level to admitted level
call adviser.`CalculateStudentGPA`('2245141010', 47, 1, 4);
-- Same but as a function
select `CalculateStudentGPA`('2245141010', 47, 1, 4);

-- Returns Needed Parameters to determine if student is elligible for graduation: Compare Required CHs with Acheived CHs
call adviser.`CheckStudentForGraduation`('2245141010');
-- Equivalent but as a function
select `isEligibleForGraduation`('2145141010', 8);

-- Returns Current Student Level
call adviser.`GetStudentLevel`('2245141010');

-- Returns Valid Registered CHs at a given semester
call adviser.`GetValidRegisteredCHs`('2245141010', 47);

-- Returns Valid Registered CHs at a given semester, without counting repeated courses
call adviser.`GetValidRegisteredCHsWithoutRepeatedCourses`('2245141010', 47);