const students = [
    { student_id: 1, numer_albumu: '12345678' },
    { student_id: 2, numer_albumu: '87654321' },
    { student_id: 3, numer_albumu: '11223344' },
    { student_id: 4, numer_albumu: '22334455' },
    { student_id: 5, numer_albumu: '33445566' },
    { student_id: 6, numer_albumu: '44556677' },
    { student_id: 7, numer_albumu: '55667788' },
    { student_id: 8, numer_albumu: '66778899' },
];

const groups = [
    { grupa_id: 1, nazwa: 'Grupa A', numer: 'A1' },
    { grupa_id: 2, nazwa: 'Grupa B', numer: 'B1' },
    { grupa_id: 3, nazwa: 'Grupa C', numer: 'C1' },
];

const studentGroups = [
    { student_id: 1, grupa_id: 1 },
    { student_id: 2, grupa_id: 2 },
    { student_id: 3, grupa_id: 1 },
    { student_id: 4, grupa_id: 2 },
    { student_id: 5, grupa_id: 3 },
    { student_id: 6, grupa_id: 3 },
    { student_id: 7, grupa_id: 3 },
    { student_id: 8, grupa_id: 3 },
];

const lecturers = [
    { wykladowca_id: 1, imie: 'Jan', nazwisko: 'Kowalski' },
    { wykladowca_id: 2, imie: 'Anna', nazwisko: 'Nowak' },
    { wykladowca_id: 3, imie: 'Piotr', nazwisko: 'Wiśniewski' },
    { wykladowca_id: 4, imie: 'Maria', nazwisko: 'Zielińska' },
    { wykladowca_id: 5, imie: 'Tomasz', nazwisko: 'Dąbrowski' },
    { wykladowca_id: 6, imie: 'Agnieszka', nazwisko: 'Wójcik' },
    { wykladowca_id: 7, imie: 'Robert', nazwisko: 'Kozłowski' }
];


const rooms = [
    { sala_id: 1, numer: '101', wydzial: 'Wydział Matematyki', budynek: 'A' },
    { sala_id: 2, numer: '202', wydzial: 'Wydział Fizyki', budynek: 'B' },
];

const courses = [
    { nazwa_kursu: "Matematyka", forma_zajec: "Wykład", data: "2025-01-13", godzina: "08:15", sala_id: 101, wykladowca_id: 1, grupa_id: 1 },
    { nazwa_kursu: "Matematyka", forma_zajec: "Laboratorium", data: "2025-01-13", godzina: "10:15", sala_id: 101, wykladowca_id: 1, grupa_id: 1 },
    { nazwa_kursu: "Matematyka", forma_zajec: "Laboratorium", data: "2025-01-13", godzina: "16:15", sala_id: 101, wykladowca_id: 1, grupa_id: 1 },
    { nazwa_kursu: "Fizyka", forma_zajec: "Ćwiczenia", data: "2025-01-14", godzina: "10:15", sala_id: 102, wykladowca_id: 2, grupa_id: 1 },
    { nazwa_kursu: "Chemia", forma_zajec: "Laboratorium", data: "2025-01-15", godzina: "12:15", sala_id: 103, wykladowca_id: 3, grupa_id: 2 },
    { nazwa_kursu: "Biologia", forma_zajec: "Wykład", data: "2025-01-16", godzina: "14:15", sala_id: 104, wykladowca_id: 4, grupa_id: 2 },
    { nazwa_kursu: "Informatyka", forma_zajec: "Projekt", data: "2025-01-17", godzina: "16:15", sala_id: 105, wykladowca_id: 5, grupa_id: 1 },
    { nazwa_kursu: "Historia", forma_zajec: "Seminarium", data: "2025-01-18", godzina: "18:15", sala_id: 106, wykladowca_id: 6, grupa_id: 3 },
    { nazwa_kursu: "Geografia", forma_zajec: "Wykład", data: "2025-01-19", godzina: "08:15", sala_id: 107, wykladowca_id: 7, grupa_id: 3 },
];

const daysOfWeek = {
    "2025-01-13": 1,
    "2025-01-14": 2,
    "2025-01-15": 3,
    "2025-01-16": 4,
    "2025-01-17": 5,
    "2025-01-18": 6,
    "2025-01-19": 7,
};

const hours = {
    "08:15": 1,
    "10:15": 2,
    "12:15": 3,
    "14:15": 4,
    "16:15": 5,
    "18:15": 6,
};

document.addEventListener("DOMContentLoaded", function () {
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const studentNumber = getQueryParam("studentNumber");
    console.log(studentNumber);

    if (studentNumber) {
        const schedule = getStudentSchedule(studentNumber);

        if (schedule && schedule.length > 0) {
            schedule.forEach((item, index) => {
                const dayIndex = daysOfWeek[item.data];
                const hourIndex = hours[item.godzina];
                if (dayIndex && hourIndex) {
                    const cell = document.querySelector(`#schedule-table tr:nth-child(${hourIndex + 1}) td:nth-child(${dayIndex + 1})`);
                    if (cell) {
                        cell.innerHTML = `
          <strong>${item.nazwa_kursu}</strong> (${item.forma_zajec.charAt(0)})<br>
        `;
                    }
                }
            });
        } else {
            console.log('Schedule not found.');
        }
    } else {
        console.log("Numer albumu nie został podany.");
    }
});


const currentDate = document.querySelector("#current-date");
currentDate.textContent = new Date().toDateString();

function fillTable(courses) {
    courses.forEach(course => {
        const dayIndex = daysOfWeek[course.data];
        const hourIndex = hours[course.godzina];
        if (dayIndex && hourIndex) {
            const cell = document.querySelector(`#schedule-table tr:nth-child(${hourIndex + 1}) td:nth-child(${dayIndex + 1})`);
            if (cell) {
                cell.innerHTML = `
  <strong>${course.nazwa_kursu}</strong> (${course.forma_zajec.charAt(0)})<br>
`;
            }
        }
    });
}

function clearTable() {
    const rows = document.querySelectorAll('#schedule-table tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');

        cells.forEach((cell, index) => {
            if (index > 0) {
                cell.textContent = '';
            }
        });
    });
}

function getStudentSchedule(numerAlbumu) {
    const student = students.find(s => s.numer_albumu === numerAlbumu);
    if (!student) {
        console.log('Student nie instnieje.');
        return;
    }

    const studentGroupIds = studentGroups
        .filter(sg => sg.student_id === student.student_id)
        .map(sg => sg.grupa_id);

    const studentClasses = courses.filter(c => studentGroupIds.includes(c.grupa_id));

    const schedule = studentClasses.map(c => {
        const group = groups.find(g => g.grupa_id === c.grupa_id);
        const lecturer = lecturers.find(l => l.wykladowca_id === c.wykladowca_id);
        const room = rooms.find(r => r.sala_id === c.sala_id);


        return {
            nazwa_kursu: c.nazwa_kursu,
            forma_zajec: c.forma_zajec,
            data: c.data,
            godzina: c.godzina,
            sala: room ? `${room.numer}, ${room.budynek}, ${room.wydzial}` : 'Undefined',
            wykladowca: lecturer ? `${lecturer.imie} ${lecturer.nazwisko}` : 'Undefined',
            grupa: group ? `${group.nazwa} (${group.numer})` : 'Undefined'
        };
    });

    return schedule;
}

function getLecturerSchedule(lecturerName) {
    const lecturer = lecturers.find(
        l => `${l.imie} ${l.nazwisko}`.toLowerCase() === lecturerName.toLowerCase()
    );
    if (!lecturer) {
        console.log('Wykładowca nie istnieje.');
        return;
    }

    const lecturerClasses = courses.filter(c => c.wykladowca_id === lecturer.wykladowca_id);

    const schedule = lecturerClasses.map(c => {
        const group = groups.find(g => g.grupa_id === c.grupa_id);
        const room = rooms.find(r => r.sala_id === c.sala_id);

        return {
            nazwa_kursu: c.nazwa_kursu,
            forma_zajec: c.forma_zajec,
            data: c.data,
            godzina: c.godzina,
            sala: room ? `${room.numer}, ${room.budynek}, ${room.wydzial}` : 'Undefined',
            grupa: group ? `${group.nazwa} (${group.numer})` : 'Undefined'
        };
    });

    return schedule;
}

const lecturerForm = document.querySelector("#lecturerForm");
const lecturerInput = lecturerForm.querySelector("#lecturerName");

lecturerForm.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
        clearTable();
        let lecturerName = lecturerInput.value;
        const schedule = getLecturerSchedule(lecturerName);

        if (schedule && schedule.length > 0) {
            console.log(`lecturer schedule ${lecturerName}:`);
            schedule.forEach(item => {
                const dayIndex = daysOfWeek[item.data];
                const hourIndex = hours[item.godzina];
                if (dayIndex && hourIndex) {
                    const cell = document.querySelector(
                        `#schedule-table tr:nth-child(${hourIndex + 1}) td:nth-child(${dayIndex + 1})`
                    );
                    if (cell) {
                        cell.innerHTML = `
              <strong>${item.nazwa_kursu}</strong> (${item.forma_zajec.charAt(0)})
            `;
                    }
                }
            });
        } else {
            console.log("Plan zajęć nie istnieje.");
        }
    }
});

function getCourseSchedule(courseName) {
    const course = courses.find(c => c.nazwa_kursu.toLowerCase() === courseName.toLowerCase());
    if (!course) {
        console.log('Przedmiot nie istnieje.');
        return;
    }

    const courseSchedule = courses.filter(c => c.nazwa_kursu === course.nazwa_kursu);

    const schedule = courseSchedule.map(c => {
        const group = groups.find(g => g.grupa_id === c.grupa_id);
        const room = rooms.find(r => r.sala_id === c.sala_id);

        return {
            nazwa_kursu: c.nazwa_kursu,
            forma_zajec: c.forma_zajec,
            data: c.data,
            godzina: c.godzina,
            sala: room ? `${room.numer}, ${room.budynek}, ${room.wydzial}` : 'Undefined',
            grupa: group ? `${group.nazwa} (${group.numer})` : 'Undefined'
        };
    });

    return schedule;
}

const courseForm = document.querySelector("#courseNameForm");
const courseInput = courseForm.querySelector("#courseName");

courseForm.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
        clearTable();
        let courseName = courseInput.value;
        const schedule = getCourseSchedule(courseName);

        if (schedule && schedule.length > 0) {
            console.log(`Plan zajęć dla przedmiotu ${courseName}:`);
            schedule.forEach(item => {
                const dayIndex = daysOfWeek[item.data];
                const hourIndex = hours[item.godzina];
                if (dayIndex && hourIndex) {
                    const cell = document.querySelector(
                        `#schedule-table tr:nth-child(${hourIndex + 1}) td:nth-child(${dayIndex + 1})`
                    );
                    if (cell) {
                        cell.innerHTML = `
              <strong>${item.nazwa_kursu}</strong> (${item.forma_zajec.charAt(0)})
            `;
                    }
                }
            });
        } else {
            console.log("Plan zajęć nie istnieje.");
        }
    }
});

function getGroupSchedule(groupName) {
    const group = groups.find(g => g.nazwa.toLowerCase() === groupName.toLowerCase());
    if (!group) {
        console.log('Grupa nie istnieje.');
        return;
    }

    const groupSchedule = courses.filter(c => c.grupa_id === group.grupa_id);

    const schedule = groupSchedule.map(c => {
        const lecturer = lecturers.find(l => l.wykladowca_id === c.wykladowca_id);
        const room = rooms.find(r => r.sala_id === c.sala_id);

        return {
            nazwa_kursu: c.nazwa_kursu,
            forma_zajec: c.forma_zajec,
            data: c.data,
            godzina: c.godzina,
            sala: room ? `${room.numer}, ${room.budynek}, ${room.wydzial}` : 'Undefined',
            wykladowca: lecturer ? `${lecturer.imie} ${lecturer.nazwisko}` : 'Undefined'
        };
    });

    return schedule;
}

const groupForm = document.querySelector("#groupForm");
const groupInput = groupForm.querySelector("#group");

groupForm.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
        clearTable();
        let groupName = groupInput.value;
        const schedule = getGroupSchedule(groupName);

        if (schedule && schedule.length > 0) {
            console.log(`Plan zajęć dla grupy ${groupName}:`);
            schedule.forEach(item => {
                const dayIndex = daysOfWeek[item.data];
                const hourIndex = hours[item.godzina];
                if (dayIndex && hourIndex) {
                    const cell = document.querySelector(
                        `#schedule-table tr:nth-child(${hourIndex + 1}) td:nth-child(${dayIndex + 1})`
                    );
                    if (cell) {
                        cell.innerHTML = `
              <strong>${item.nazwa_kursu}</strong> (${item.forma_zajec.charAt(0)})
            `;
                    }
                }
            });
        } else {
            console.log("Plan zajęć nie istnieje.");
        }
    }
});

function getCourseTypeSchedule(courseType) {
    const courseSchedule = courses.filter(c => c.forma_zajec.toLowerCase() === courseType.toLowerCase());

    const schedule = courseSchedule.map(c => {
        const group = groups.find(g => g.grupa_id === c.grupa_id);
        const room = rooms.find(r => r.sala_id === c.sala_id);

        return {
            nazwa_kursu: c.nazwa_kursu,
            forma_zajec: c.forma_zajec,
            data: c.data,
            godzina: c.godzina,
            sala: room ? `${room.numer}, ${room.budynek}, ${room.wydzial}` : 'Undefined',
            grupa: group ? `${group.nazwa} (${group.numer})` : 'Undefined'
        };
    });

    return schedule;
}

function getRoomSchedule(roomName) {
    const room = rooms.find(r => r.numer.toLowerCase() === roomName.toLowerCase());
    console.log(room)
    if (!room) {
        console.log('Sala nie istnieje.');
        return;
    }

    const roomSchedule = courses.filter(c => c.sala_id === room.sala_id);
    console.log('paschalko' + roomSchedule)

    const schedule = roomSchedule.map(c => {
        const lecturer = lecturers.find(l => l.wykladowca_id === c.wykladowca_id);
        const group = groups.find(g => g.grupa_id === c.grupa_id);

        return {
            nazwa_kursu: c.nazwa_kursu,
            forma_zajec: c.forma_zajec,
            data: c.data,
            godzina: c.godzina,
            grupa: group ? `${group.nazwa} (${group.numer})` : 'Undefined',
            wykladowca: lecturer ? `${lecturer.imie} ${lecturer.nazwisko}` : 'Undefined'
        };
    });

    return schedule;
}

const roomForm = document.querySelector("#roomForm");
const roomInput = roomForm.querySelector("#room");

roomForm.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
        clearTable();
        let roomName = roomInput.value;
        const schedule = getRoomSchedule(roomName);

        if (schedule && schedule.length > 0) {
            console.log(`Plan zajęć dla sali ${roomName}:`);
            schedule.forEach(item => {
                const dayIndex = daysOfWeek[item.data];
                const hourIndex = hours[item.godzina];
                if (dayIndex && hourIndex) {
                    const cell = document.querySelector(
                        `#schedule-table tr:nth-child(${hourIndex + 1}) td:nth-child(${dayIndex + 1})`
                    );
                    if (cell) {
                        cell.innerHTML = `
              <strong>${item.nazwa_kursu}</strong> (${item.forma_zajec.charAt(0)})
            `;
                    }
                }
            });
        } else {
            console.log("Plan zajęć nie istnieje.");
        }
    }
});

const studentNumberForm = document.querySelector("#studentNumberForm");
const numberInput = studentNumberForm.querySelector('input');
studentNumberForm.addEventListener('keydown', function () {
    if (event.key === 'Enter') {
        event.preventDefault();
        clearTable();
        let numerAlbumu = numberInput.value;
        const schedule = getStudentSchedule(numerAlbumu);
        const studentNumberForm = document.querySelector('#studentNumberForm');
        console.log(numerAlbumu)
        if (schedule && schedule.length > 0) {
            schedule.forEach((item, index) => {
                const dayIndex = daysOfWeek[item.data];
                const hourIndex = hours[item.godzina];
                if (dayIndex && hourIndex) {
                    const cell = document.querySelector(`#schedule-table tr:nth-child(${hourIndex + 1}) td:nth-child(${dayIndex + 1})`);
                    if (cell) {
                        cell.innerHTML = `
          <strong>${item.nazwa_kursu}</strong> (${item.forma_zajec.charAt(0)})<br>
        `;
                    }
                }
            });
        } else {
            console.log('Schedule not found.');
        }
    }
});