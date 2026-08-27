<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Admin System',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+1 555-0100',
            ]
        );

        // 2. Departments
        $cardiology = Department::firstOrCreate(
            ['code' => 'CARD-01'],
            [
                'name' => 'Cardiology',
                'description' => 'Heart health, cardiovascular disorders, and cardiac surgery.',
                'head_doctor' => 'Dr. Robert Chen',
            ]
        );

        $neurology = Department::firstOrCreate(
            ['code' => 'NEUR-02'],
            [
                'name' => 'Neurology',
                'description' => 'Brain, spinal cord, and nervous system care.',
                'head_doctor' => 'Dr. Sarah Jenkins',
            ]
        );

        $pediatrics = Department::firstOrCreate(
            ['code' => 'PED-03'],
            [
                'name' => 'Pediatrics',
                'description' => 'Child health care, vaccinations, and infant wellness.',
                'head_doctor' => 'Dr. Michael Vance',
            ]
        );

        $orthopedics = Department::firstOrCreate(
            ['code' => 'ORTH-04'],
            [
                'name' => 'Orthopedics',
                'description' => 'Bone health, joint surgery, and sports medicine.',
                'head_doctor' => 'Dr. Elena Rostova',
            ]
        );

        $emergency = Department::firstOrCreate(
            ['code' => 'EMRG-05'],
            [
                'name' => 'Emergency & Trauma',
                'description' => '24/7 critical emergency care and urgent surgery.',
                'head_doctor' => 'Dr. Marcus Brody',
            ]
        );

        // 3. Doctors
        $doc1 = Doctor::firstOrCreate(
            ['email' => 'robert.chen@hospital.com'],
            [
                'name' => 'Dr. Robert Chen',
                'department_id' => $cardiology->id,
                'specialization' => 'Interventional Cardiologist',
                'phone' => '+1 555-0101',
                'consultation_fee' => 150.00,
                'available_days' => 'Mon, Wed, Fri',
                'available_time' => '08:00 AM - 02:00 PM',
                'status' => 'active',
            ]
        );

        $doc2 = Doctor::firstOrCreate(
            ['email' => 'sarah.jenkins@hospital.com'],
            [
                'name' => 'Dr. Sarah Jenkins',
                'department_id' => $neurology->id,
                'specialization' => 'Senior Neurosurgeon',
                'phone' => '+1 555-0102',
                'consultation_fee' => 180.00,
                'available_days' => 'Tue, Thu, Sat',
                'available_time' => '10:00 AM - 04:00 PM',
                'status' => 'active',
            ]
        );

        $doc3 = Doctor::firstOrCreate(
            ['email' => 'michael.vance@hospital.com'],
            [
                'name' => 'Dr. Michael Vance',
                'department_id' => $pediatrics->id,
                'specialization' => 'Pediatric Specialist',
                'phone' => '+1 555-0103',
                'consultation_fee' => 100.00,
                'available_days' => 'Mon, Tue, Wed, Thu, Fri',
                'available_time' => '09:00 AM - 05:00 PM',
                'status' => 'active',
            ]
        );

        $doc4 = Doctor::firstOrCreate(
            ['email' => 'elena.rostova@hospital.com'],
            [
                'name' => 'Dr. Elena Rostova',
                'department_id' => $orthopedics->id,
                'specialization' => 'Orthopedic Surgeon',
                'phone' => '+1 555-0104',
                'consultation_fee' => 140.00,
                'available_days' => 'Mon, Thu, Sat',
                'available_time' => '11:00 AM - 05:00 PM',
                'status' => 'active',
            ]
        );

        // 4. Patients
        $pat1 = Patient::firstOrCreate(
            ['mrn' => 'PAT-80124'],
            [
                'name' => 'Johnathan Miller',
                'age' => 45,
                'gender' => 'Male',
                'blood_group' => 'O+',
                'phone' => '+1 555-0201',
                'email' => 'john.miller@example.com',
                'address' => '742 Evergreen Terrace, Springfield',
                'medical_history' => 'Hypertension, Mild Asthma',
            ]
        );

        $pat2 = Patient::firstOrCreate(
            ['mrn' => 'PAT-80125'],
            [
                'name' => 'Emma Watson',
                'age' => 29,
                'gender' => 'Female',
                'blood_group' => 'A+',
                'phone' => '+1 555-0202',
                'email' => 'emma.watson@example.com',
                'address' => '123 Baker Street, London',
                'medical_history' => 'No major chronic illnesses.',
            ]
        );

        $pat3 = Patient::firstOrCreate(
            ['mrn' => 'PAT-80126'],
            [
                'name' => 'Alexander Wright',
                'age' => 62,
                'gender' => 'Male',
                'blood_group' => 'B-',
                'phone' => '+1 555-0203',
                'email' => 'alex.wright@example.com',
                'address' => '55 Ocean Drive, Miami',
                'medical_history' => 'Type 2 Diabetes, High Cholesterol',
            ]
        );

        $pat4 = Patient::firstOrCreate(
            ['mrn' => 'PAT-80127'],
            [
                'name' => 'Sophia Martinez',
                'age' => 8,
                'gender' => 'Female',
                'blood_group' => 'AB+',
                'phone' => '+1 555-0204',
                'email' => 'parent.martinez@example.com',
                'address' => '88 Pine Street, Seattle',
                'medical_history' => 'Seasonal allergies.',
            ]
        );

        // 5. Appointments
        $apt1 = Appointment::firstOrCreate(
            ['appointment_number' => 'APT-901'],
            [
                'patient_id' => $pat1->id,
                'doctor_id' => $doc1->id,
                'appointment_date' => now()->addDays(1)->setHour(10)->setMinute(0),
                'status' => 'Confirmed',
                'symptoms' => 'Chest tightness, elevated blood pressure',
                'notes' => 'Patient requested morning slot.',
            ]
        );

        $apt2 = Appointment::firstOrCreate(
            ['appointment_number' => 'APT-902'],
            [
                'patient_id' => $pat2->id,
                'doctor_id' => $doc3->id,
                'appointment_date' => now()->setHour(14)->setMinute(30),
                'status' => 'Scheduled',
                'symptoms' => 'Routine wellness checkup & fever review',
                'notes' => 'First consultation.',
            ]
        );

        $apt3 = Appointment::firstOrCreate(
            ['appointment_number' => 'APT-903'],
            [
                'patient_id' => $pat3->id,
                'doctor_id' => $doc2->id,
                'appointment_date' => now()->subDays(2)->setHour(11)->setMinute(0),
                'status' => 'Completed',
                'symptoms' => 'Frequent migraines and dizziness',
                'notes' => 'MRI scans requested.',
            ]
        );

        // 6. Prescriptions
        Prescription::firstOrCreate(
            ['appointment_id' => $apt3->id],
            [
                'patient_id' => $pat3->id,
                'doctor_id' => $doc2->id,
                'diagnosis' => 'Migraine with aura & chronic tension',
                'medicines' => "1. Sumatriptan 50mg - 1 tab as needed\n2. Magnesium Glycinate 400mg - Daily before bed",
                'dosage_instructions' => 'Take Sumatriptan at onset of aura with plenty of water. Avoid caffeine.',
                'lab_tests' => 'Brain MRI with contrast, Full Blood Count',
                'prescription_date' => now()->subDays(2),
            ]
        );

        // 7. Rooms
        Room::firstOrCreate(
            ['room_number' => 'ICU-101'],
            [
                'type' => 'ICU',
                'status' => 'Occupied',
                'daily_rate' => 500.00,
                'patient_id' => $pat3->id,
            ]
        );

        Room::firstOrCreate(
            ['room_number' => 'PRIV-204'],
            [
                'type' => 'Private Room',
                'status' => 'Available',
                'daily_rate' => 250.00,
                'patient_id' => null,
            ]
        );

        Room::firstOrCreate(
            ['room_number' => 'GEN-301'],
            [
                'type' => 'General Ward',
                'status' => 'Available',
                'daily_rate' => 100.00,
                'patient_id' => null,
            ]
        );

        Room::firstOrCreate(
            ['room_number' => 'GEN-302'],
            [
                'type' => 'General Ward',
                'status' => 'Available',
                'daily_rate' => 100.00,
                'patient_id' => null,
            ]
        );

        // 8. Bills
        Bill::firstOrCreate(
            ['invoice_number' => 'INV-5501'],
            [
                'patient_id' => $pat3->id,
                'appointment_id' => $apt3->id,
                'consultation_fee' => 180.00,
                'room_charge' => 500.00,
                'medicine_charge' => 75.00,
                'tax' => 37.75,
                'total_amount' => 792.75,
                'status' => 'Paid',
                'payment_method' => 'Credit Card',
                'bill_date' => now()->subDays(2),
            ]
        );

        Bill::firstOrCreate(
            ['invoice_number' => 'INV-5502'],
            [
                'patient_id' => $pat1->id,
                'appointment_id' => $apt1->id,
                'consultation_fee' => 150.00,
                'room_charge' => 0.00,
                'medicine_charge' => 45.00,
                'tax' => 9.75,
                'total_amount' => 204.75,
                'status' => 'Unpaid',
                'payment_method' => 'Insurance',
                'bill_date' => now(),
            ]
        );
    }
}
