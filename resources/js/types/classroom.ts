import type { COURSE_ENROLLMENT_STATUS } from '@/consts/classroom';

export type CourseEnrollmentStatus = keyof typeof COURSE_ENROLLMENT_STATUS;
export type CourseEnrollmentStatusValue =
    (typeof COURSE_ENROLLMENT_STATUS)[CourseEnrollmentStatus];
