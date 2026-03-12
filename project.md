# 게시판 + 로그인 시스템

## 프로젝트 개요
LAMP 스택을 활용한 회원 로그인 및 게시판 웹 애플리케이션

## 기술 스택
- OS: Zorin OS (Linux)
- Web Server: Apache2
- Database: MySQL
- Backend: PHP 8.x
- Frontend: HTML, CSS, Bootstrap 5

## 주요 기능

### 회원 관리
1. 회원가입 (아이디/비밀번호/이메일)
2. 로그인 / 로그아웃
3. 세션 관리
4. 비밀번호 암호화 (password_hash)

### 게시판
1. 글 목록 조회 (페이징 처리)
2. 글 작성 (로그인 필요)
3. 글 수정 (본인만 가능)
4. 글 삭제 (본인만 가능)
5. 글 상세 보기
6. 댓글 기능

## 데이터베이스 설계

### users 테이블
| 컬럼명 | 타입 | 설명 |
|--------|------|------|
| user_id | INT (PK) | 사용자 ID |
| username | VARCHAR(50) | 아이디 |
| password | VARCHAR(255) | 암호화된 비밀번호 |
| email | VARCHAR(100) | 이메일 |
| created_at | DATETIME | 가입일 |

### posts 테이블
| 컬럼명 | 타입 | 설명 |
|--------|------|------|
| post_id | INT (PK) | 게시글 ID |
| user_id | INT (FK) | 작성자 |
| title | VARCHAR(200) | 제목 |
| content | TEXT | 내용 |
| views | INT | 조회수 |
| created_at | DATETIME | 작성일 |

### comments 테이블
| 컬럼명 | 타입 | 설명 |
|--------|------|------|
| comment_id | INT (PK) | 댓글 ID |
| post_id | INT (FK) | 게시글 ID |
| user_id | INT (FK) | 작성자 |
| content | TEXT | 댓글 내용 |
| created_at | DATETIME | 작성일 |

## 디렉토리 구조
/var/www/html/board/
├── config/
│   └── db.php          # DB 연결 설정
├── auth/
│   ├── login.php       # 로그인
│   ├── logout.php      # 로그아웃
│   └── register.php    # 회원가입
├── board/
│   ├── list.php        # 글 목록
│   ├── view.php        # 글 상세
│   ├── write.php       # 글 작성
│   └── edit.php        # 글 수정/삭제
├── includes/
│   ├── header.php      # 공통 헤더
│   └── footer.php      # 공통 푸터
└── index.php           # 메인 진입점

