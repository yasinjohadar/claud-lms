<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionBank;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\Course;
use App\Models\ProgrammingLanguage;
use Illuminate\Support\Facades\DB;

class JavaScriptQuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على كورس JavaScript
        $course = Course::where('slug', 'professional-web-development')->first();

        if (!$course) {
            $this->command->error('❌ كورس تطوير الويب غير موجود! يرجى تشغيل CourseCatalogSeeder أولاً');
            return;
        }

        // الحصول على المستخدم (instructor)
        $instructor = $course->instructor ?? \App\Models\User::first();

        if (!$instructor) {
            $this->command->error('❌ لا يوجد مستخدم لإنشاء الأسئلة!');
            return;
        }

        // الحصول على أنواع الأسئلة
        $trueFalseType = QuestionType::where('name', 'true_false')->first();
        $multipleChoiceType = QuestionType::where('name', 'multiple_choice_single')->first();

        if (!$trueFalseType || !$multipleChoiceType) {
            $this->command->error('❌ أنواع الأسئلة غير موجودة! يرجى تشغيل QuestionTypeSeeder أولاً');
            return;
        }

        // الحصول على لغة البرمجة
        $jsLang = ProgrammingLanguage::where('slug', 'javascript')->first();

        if (!$jsLang) {
            $this->command->error('❌ لغة JavaScript غير موجودة! يرجى تشغيل ProgrammingLanguageSeeder أولاً');
            return;
        }

        // بدء المعاملة
        DB::beginTransaction();

        try {
            // ========== أسئلة صح وخطأ (25 سؤالاً) ==========

            $trueFalseQuestions = [
                // ES6 Basics (5 أسئلة)
                [
                    'question_text' => '<p>let و const في ES6 لهما Block Scope على عكس var</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>Arrow Functions ترث قيمة this من السياق الخارجي</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>Template Literals في JavaScript تستخدم علامات الاقتباس المفردة</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>Destructuring Assignment يسمح باستخراج قيم من Arrays و Objects</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>Spread Operator (...) يمكن استخدامه مع Objects و Arrays فقط</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],

                // Promises & Async (5 أسئلة)
                [
                    'question_text' => '<p>Promise في JavaScript يمكن أن يكون في إحدى ثلاث حالات: pending, fulfilled, rejected</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>async/await هو مجرد syntactic sugar فوق Promises</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],
                [
                    'question_text' => '<p>Promise.all() ينتظر حتى يتم resolve جميع الـ Promises أو يفشل أول واحد</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],
                [
                    'question_text' => '<p>يمكن استخدام await خارج async function</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>Promise.race() ترجع أول Promise ينتهي سواء بـ resolve أو reject</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],

                // Classes & OOP (5 أسئلة)
                [
                    'question_text' => '<p>Classes في ES6 هي في الحقيقة Functions خاصة</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>يمكن للـ Class في JavaScript أن يرث من أكثر من Class واحد (Multiple Inheritance)</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>Static Methods في Classes يتم استدعاؤها على الـ Class نفسه وليس على الـ Instance</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>Getter و Setter في Classes تسمح بالتحكم في الوصول للخصائص</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>يجب استدعاء super() في constructor قبل استخدام this عند الوراثة</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],

                // Modules & Imports (5 أسئلة)
                [
                    'question_text' => '<p>ES6 Modules تدعم Named Exports و Default Export</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>يمكن أن يكون للملف الواحد أكثر من Default Export</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>import * as name يستورد جميع exports من ملف ما كـ Object</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>Dynamic Import في JavaScript يرجع Promise</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],
                [
                    'question_text' => '<p>Modules في JavaScript تعمل في strict mode افتراضياً</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],

                // Advanced Features (5 أسئلة)
                [
                    'question_text' => '<p>Proxy في JavaScript يسمح بتخصيص سلوك العمليات الأساسية على Objects</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],
                [
                    'question_text' => '<p>Symbol هو نوع بيانات بدائي (Primitive) جديد في ES6</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>WeakMap تحتفظ بمفاتيحها من Garbage Collection</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],
                [
                    'question_text' => '<p>Generator Functions تستخدم الكلمة المفتاحية function*</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>Optional Chaining (?.) يمنع الأخطاء عند الوصول لخصائص قد لا تكون موجودة</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
            ];

            foreach ($trueFalseQuestions as $questionData) {
                $question = QuestionBank::create([
                    'question_type_id' => $trueFalseType->id,
                    'course_id' => $course->id,
                    'question_text' => $questionData['question_text'],
                    'explanation' => null,
                    'difficulty_level' => $questionData['difficulty'],
                    'default_grade' => $questionData['points'],
                    'is_active' => true,
                    'times_used' => 0,
                    'created_by' => $instructor->id,
                ]);

                // ربط السؤال بلغة JavaScript
                $question->programmingLanguages()->attach([$jsLang->id]);

                // إنشاء خيارات صح وخطأ
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => '<p>صحيح</p>',
                    'is_correct' => $questionData['correct_answer'] === 'true',
                    'grade_percentage' => $questionData['correct_answer'] === 'true' ? 100 : 0,
                    'option_order' => 1,
                ]);

                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => '<p>خطأ</p>',
                    'is_correct' => $questionData['correct_answer'] === 'false',
                    'grade_percentage' => $questionData['correct_answer'] === 'false' ? 100 : 0,
                    'option_order' => 2,
                ]);
            }

            // ========== أسئلة اختيار من متعدد (25 سؤالاً) ==========

            $multipleChoiceQuestions = [
                // ES6 Syntax (5 أسئلة)
                [
                    'question_text' => '<p>ما هي الطريقة الصحيحة لتعريف Arrow Function؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>const func = () => {}</p>', 'is_correct' => true],
                        ['text' => '<p>const func = function() => {}</p>', 'is_correct' => false],
                        ['text' => '<p>const func => {}</p>', 'is_correct' => false],
                        ['text' => '<p>const func = -> {}</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>كيف تقوم بـ Destructuring لأول عنصرين من Array؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>const [a, b] = array;</p>', 'is_correct' => true],
                        ['text' => '<p>const {a, b} = array;</p>', 'is_correct' => false],
                        ['text' => '<p>const (a, b) = array;</p>', 'is_correct' => false],
                        ['text' => '<p>const [0, 1] = array;</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هي الصيغة الصحيحة لـ Template Literal؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>`Hello ${name}`</p>', 'is_correct' => true],
                        ['text' => '<p>"Hello ${name}"</p>', 'is_correct' => false],
                        ['text' => '<p>\'Hello ${name}\'</p>', 'is_correct' => false],
                        ['text' => '<p>"Hello " + ${name}</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>كيف تقوم بنسخ Array باستخدام Spread Operator؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>const newArr = [...oldArr];</p>', 'is_correct' => true],
                        ['text' => '<p>const newArr = ...oldArr;</p>', 'is_correct' => false],
                        ['text' => '<p>const newArr = {oldArr};</p>', 'is_correct' => false],
                        ['text' => '<p>const newArr = [oldArr];</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو ناتج: const {x = 10} = {};</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>x = 10</p>', 'is_correct' => true],
                        ['text' => '<p>x = undefined</p>', 'is_correct' => false],
                        ['text' => '<p>Error</p>', 'is_correct' => false],
                        ['text' => '<p>x = null</p>', 'is_correct' => false],
                    ],
                ],

                // Promises & Async (5 أسئلة)
                [
                    'question_text' => '<p>كيف تقوم بإنشاء Promise جديد؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>new Promise((resolve, reject) => {})</p>', 'is_correct' => true],
                        ['text' => '<p>Promise.create((resolve, reject) => {})</p>', 'is_correct' => false],
                        ['text' => '<p>new Promise(resolve, reject)</p>', 'is_correct' => false],
                        ['text' => '<p>Promise((resolve, reject) => {})</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي من الطرق التالية تُستخدم لمعالجة الأخطاء في Promise؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>.catch()</p>', 'is_correct' => true],
                        ['text' => '<p>.error()</p>', 'is_correct' => false],
                        ['text' => '<p>.fail()</p>', 'is_correct' => false],
                        ['text' => '<p>.onError()</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هي الطريقة الصحيحة لاستخدام async/await؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>async function f() { await promise; }</p>', 'is_correct' => true],
                        ['text' => '<p>function f() { await promise; }</p>', 'is_correct' => false],
                        ['text' => '<p>async function f() { wait promise; }</p>', 'is_correct' => false],
                        ['text' => '<p>function async f() { await promise; }</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو الفرق بين Promise.all() و Promise.allSettled()؟</p>',
                    'difficulty' => 'hard',
                    'points' => 4,
                    'options' => [
                        ['text' => '<p>allSettled لا يرفض حتى لو فشل بعض الـ Promises</p>', 'is_correct' => true],
                        ['text' => '<p>allSettled أسرع من all</p>', 'is_correct' => false],
                        ['text' => '<p>all لا يرفض عند فشل Promise</p>', 'is_correct' => false],
                        ['text' => '<p>لا يوجد فرق</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>كيف تعالج الأخطاء في async function؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>try/catch</p>', 'is_correct' => true],
                        ['text' => '<p>.catch()</p>', 'is_correct' => false],
                        ['text' => '<p>if/else</p>', 'is_correct' => false],
                        ['text' => '<p>error handler</p>', 'is_correct' => false],
                    ],
                ],

                // Arrays & Objects Methods (5 أسئلة)
                [
                    'question_text' => '<p>أي من الطرق التالية تُغير Array الأصلي؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>push()</p>', 'is_correct' => true],
                        ['text' => '<p>map()</p>', 'is_correct' => false],
                        ['text' => '<p>filter()</p>', 'is_correct' => false],
                        ['text' => '<p>concat()</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو ناتج: [1, 2, 3].reduce((a, b) => a + b, 0)</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>6</p>', 'is_correct' => true],
                        ['text' => '<p>0</p>', 'is_correct' => false],
                        ['text' => '<p>[1, 2, 3]</p>', 'is_correct' => false],
                        ['text' => '<p>undefined</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي طريقة تُستخدم للبحث عن عنصر في Array وإرجاع العنصر نفسه؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>find()</p>', 'is_correct' => true],
                        ['text' => '<p>search()</p>', 'is_correct' => false],
                        ['text' => '<p>indexOf()</p>', 'is_correct' => false],
                        ['text' => '<p>get()</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>كيف تقوم بدمج Objects في ES6؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>const merged = {...obj1, ...obj2};</p>', 'is_correct' => true],
                        ['text' => '<p>const merged = obj1 + obj2;</p>', 'is_correct' => false],
                        ['text' => '<p>const merged = [obj1, obj2];</p>', 'is_correct' => false],
                        ['text' => '<p>const merged = Object.merge(obj1, obj2);</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو ناتج: Object.keys({a: 1, b: 2})</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>[\'a\', \'b\']</p>', 'is_correct' => true],
                        ['text' => '<p>[1, 2]</p>', 'is_correct' => false],
                        ['text' => '<p>{a: 1, b: 2}</p>', 'is_correct' => false],
                        ['text' => '<p>2</p>', 'is_correct' => false],
                    ],
                ],

                // Classes & Prototypes (5 أسئلة)
                [
                    'question_text' => '<p>كيف تقوم بتعريف Class في ES6؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>class MyClass {}</p>', 'is_correct' => true],
                        ['text' => '<p>function class MyClass {}</p>', 'is_correct' => false],
                        ['text' => '<p>new Class MyClass {}</p>', 'is_correct' => false],
                        ['text' => '<p>const MyClass = class()</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>كيف تقوم بالوراثة من Class آخر؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>class Child extends Parent {}</p>', 'is_correct' => true],
                        ['text' => '<p>class Child inherits Parent {}</p>', 'is_correct' => false],
                        ['text' => '<p>class Child : Parent {}</p>', 'is_correct' => false],
                        ['text' => '<p>class Child(Parent) {}</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>كيف تقوم بتعريف Static Method؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>static methodName() {}</p>', 'is_correct' => true],
                        ['text' => '<p>class.methodName() {}</p>', 'is_correct' => false],
                        ['text' => '<p>const methodName() {}</p>', 'is_correct' => false],
                        ['text' => '<p>static: methodName() {}</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هي الكلمة المفتاحية لاستدعاء constructor الخاص بالـ Parent Class؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>super()</p>', 'is_correct' => true],
                        ['text' => '<p>parent()</p>', 'is_correct' => false],
                        ['text' => '<p>base()</p>', 'is_correct' => false],
                        ['text' => '<p>this.parent()</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>كيف تقوم بتعريف Getter في Class؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>get propertyName() {}</p>', 'is_correct' => true],
                        ['text' => '<p>getter propertyName() {}</p>', 'is_correct' => false],
                        ['text' => '<p>get: propertyName() {}</p>', 'is_correct' => false],
                        ['text' => '<p>propertyName.get() {}</p>', 'is_correct' => false],
                    ],
                ],

                // Modules & Advanced (5 أسئلة)
                [
                    'question_text' => '<p>كيف تقوم بـ Default Export في Module؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>export default myFunction;</p>', 'is_correct' => true],
                        ['text' => '<p>export myFunction as default;</p>', 'is_correct' => false],
                        ['text' => '<p>default export myFunction;</p>', 'is_correct' => false],
                        ['text' => '<p>module.exports = myFunction;</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>كيف تستورد Named Export؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>import { myFunc } from \'./module\';</p>', 'is_correct' => true],
                        ['text' => '<p>import myFunc from \'./module\';</p>', 'is_correct' => false],
                        ['text' => '<p>import * as myFunc from \'./module\';</p>', 'is_correct' => false],
                        ['text' => '<p>const myFunc = require(\'./module\');</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو Symbol في JavaScript؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>نوع بيانات بدائي يمثل قيمة فريدة</p>', 'is_correct' => true],
                        ['text' => '<p>نوع بيانات للأرقام</p>', 'is_correct' => false],
                        ['text' => '<p>نوع بيانات للنصوص</p>', 'is_correct' => false],
                        ['text' => '<p>وظيفة لإنشاء رموز</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو استخدام Generator Function؟</p>',
                    'difficulty' => 'hard',
                    'points' => 4,
                    'options' => [
                        ['text' => '<p>إنشاء iterator قابل للإيقاف والاستئناف</p>', 'is_correct' => true],
                        ['text' => '<p>إنشاء وظائف عشوائية</p>', 'is_correct' => false],
                        ['text' => '<p>توليد أرقام تلقائياً</p>', 'is_correct' => false],
                        ['text' => '<p>إنشاء classes تلقائياً</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو Nullish Coalescing Operator؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>??</p>', 'is_correct' => true],
                        ['text' => '<p>||</p>', 'is_correct' => false],
                        ['text' => '<p>&&</p>', 'is_correct' => false],
                        ['text' => '<p>!!</p>', 'is_correct' => false],
                    ],
                ],
            ];

            foreach ($multipleChoiceQuestions as $questionData) {
                $question = QuestionBank::create([
                    'question_type_id' => $multipleChoiceType->id,
                    'course_id' => $course->id,
                    'question_text' => $questionData['question_text'],
                    'explanation' => null,
                    'difficulty_level' => $questionData['difficulty'],
                    'default_grade' => $questionData['points'],
                    'is_active' => true,
                    'times_used' => 0,
                    'created_by' => $instructor->id,
                ]);

                // ربط السؤال بلغة JavaScript
                $question->programmingLanguages()->attach([$jsLang->id]);

                // إنشاء الخيارات
                foreach ($questionData['options'] as $optionIndex => $option) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $option['text'],
                        'is_correct' => $option['is_correct'],
                        'grade_percentage' => $option['is_correct'] ? 100 : 0,
                        'option_order' => $optionIndex + 1,
                    ]);
                }
            }

            DB::commit();

            $this->command->info('✅ تم إنشاء 50 سؤالاً لكورس JavaScript ES6+ بنجاح!');
            $this->command->info('📊 التوزيع: 25 أسئلة صح/خطأ + 25 أسئلة اختيار من متعدد');
            $this->command->info('📝 المواضيع: ES6 Syntax, Promises, Async/Await, Classes, Modules, Advanced Features');
            $this->command->info('🏷️  تم ربط الأسئلة بلغة البرمجة: JavaScript');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ حدث خطأ أثناء إنشاء الأسئلة: ' . $e->getMessage());
            throw $e;
        }
    }
}
