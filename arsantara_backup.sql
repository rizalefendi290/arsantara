--
-- PostgreSQL database dump
--

\restrict zN6HTeISJu2fhRAKHcFrbVlpMPP0PIEvDAENxr9RD9UG8L2Wa1q11eOFfj82Inw

-- Dumped from database version 18.3
-- Dumped by pg_dump version 18.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: agent_requests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.agent_requests (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    role character varying(255) NOT NULL,
    nama character varying(255) NOT NULL,
    nama_agen character varying(255),
    nama_pemilik_agen character varying(255),
    no_hp character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    alamat text NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.agent_requests OWNER TO postgres;

--
-- Name: agent_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.agent_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.agent_requests_id_seq OWNER TO postgres;

--
-- Name: agent_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.agent_requests_id_seq OWNED BY public.agent_requests.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: car_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.car_details (
    id bigint NOT NULL,
    listing_id bigint NOT NULL,
    brand character varying(255) NOT NULL,
    model character varying(255) NOT NULL,
    year integer NOT NULL,
    engine integer NOT NULL,
    transmission character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    fuel_type character varying(255),
    color character varying(255),
    kilometer integer,
    CONSTRAINT car_details_fuel_type_check CHECK (((fuel_type)::text = ANY ((ARRAY['bensin'::character varying, 'diesel'::character varying, 'listrik'::character varying, 'hybrid'::character varying])::text[]))),
    CONSTRAINT car_details_transmission_check CHECK (((transmission)::text = ANY ((ARRAY['manual'::character varying, 'matic'::character varying])::text[])))
);


ALTER TABLE public.car_details OWNER TO postgres;

--
-- Name: car_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.car_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.car_details_id_seq OWNER TO postgres;

--
-- Name: car_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.car_details_id_seq OWNED BY public.car_details.id;


--
-- Name: carousels; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carousels (
    id bigint NOT NULL,
    image character varying(255) NOT NULL,
    title character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    link_url character varying(2048),
    placement character varying(255) DEFAULT 'content'::character varying NOT NULL,
    page_key character varying(255),
    label character varying(255),
    text text,
    sort_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    label_color character varying(7),
    title_color character varying(7),
    text_color character varying(7),
    buttons json
);


ALTER TABLE public.carousels OWNER TO postgres;

--
-- Name: carousels_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.carousels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carousels_id_seq OWNER TO postgres;

--
-- Name: carousels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carousels_id_seq OWNED BY public.carousels.id;


--
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    is_active boolean DEFAULT true NOT NULL
);


ALTER TABLE public.categories OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categories_id_seq OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: favorites; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.favorites (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    listing_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.favorites OWNER TO postgres;

--
-- Name: favorites_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.favorites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.favorites_id_seq OWNER TO postgres;

--
-- Name: favorites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.favorites_id_seq OWNED BY public.favorites.id;


--
-- Name: job_applications; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_applications (
    id bigint NOT NULL,
    job_vacancy_id bigint NOT NULL,
    full_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    nik character varying(20) NOT NULL,
    gender character varying(20) NOT NULL,
    birth_date date NOT NULL,
    source character varying(255) NOT NULL,
    phone character varying(30) NOT NULL,
    domicile_address text NOT NULL,
    province character varying(255) NOT NULL,
    city character varying(255) NOT NULL,
    district character varying(255) NOT NULL,
    village character varying(255) NOT NULL,
    expected_salary bigint NOT NULL,
    cv_path character varying(255) NOT NULL,
    cv_original_name character varying(255) NOT NULL,
    education_level character varying(20) NOT NULL,
    education_institution character varying(255) NOT NULL,
    major character varying(255) NOT NULL,
    gpa character varying(20) NOT NULL,
    work_experience text NOT NULL,
    statement_accepted boolean DEFAULT false NOT NULL,
    privacy_accepted boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    reviewed_at timestamp(0) without time zone
);


ALTER TABLE public.job_applications OWNER TO postgres;

--
-- Name: job_applications_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.job_applications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.job_applications_id_seq OWNER TO postgres;

--
-- Name: job_applications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.job_applications_id_seq OWNED BY public.job_applications.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: job_vacancies; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_vacancies (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    employment_type character varying(255),
    location character varying(255),
    deadline date,
    description text,
    requirements text,
    apply_url character varying(2048),
    sort_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.job_vacancies OWNER TO postgres;

--
-- Name: job_vacancies_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.job_vacancies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.job_vacancies_id_seq OWNER TO postgres;

--
-- Name: job_vacancies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.job_vacancies_id_seq OWNED BY public.job_vacancies.id;


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: listing_images; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.listing_images (
    id bigint NOT NULL,
    listing_id bigint NOT NULL,
    image character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.listing_images OWNER TO postgres;

--
-- Name: listing_images_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.listing_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.listing_images_id_seq OWNER TO postgres;

--
-- Name: listing_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.listing_images_id_seq OWNED BY public.listing_images.id;


--
-- Name: listing_views; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.listing_views (
    id bigint NOT NULL,
    listing_id bigint NOT NULL,
    user_id bigint,
    session_id character varying(255),
    ip_address character varying(45),
    user_agent text,
    viewed_at timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.listing_views OWNER TO postgres;

--
-- Name: listing_views_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.listing_views_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.listing_views_id_seq OWNER TO postgres;

--
-- Name: listing_views_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.listing_views_id_seq OWNED BY public.listing_views.id;


--
-- Name: listings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.listings (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    category_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    price bigint NOT NULL,
    location character varying(255) NOT NULL,
    condition character varying(255),
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    is_featured boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    discount_price bigint,
    product_code character varying(255),
    CONSTRAINT listings_condition_check CHECK (((condition)::text = ANY ((ARRAY['baru'::character varying, 'bekas'::character varying])::text[]))),
    CONSTRAINT listings_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'aktif'::character varying, 'sold'::character varying])::text[])))
);


ALTER TABLE public.listings OWNER TO postgres;

--
-- Name: listings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.listings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.listings_id_seq OWNER TO postgres;

--
-- Name: listings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.listings_id_seq OWNED BY public.listings.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: motorcycle_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.motorcycle_details (
    id bigint NOT NULL,
    listing_id bigint NOT NULL,
    brand character varying(255) NOT NULL,
    model character varying(255) NOT NULL,
    year integer NOT NULL,
    engine integer NOT NULL,
    transmission character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT motorcycle_details_transmission_check CHECK (((transmission)::text = ANY ((ARRAY['manual'::character varying, 'matic'::character varying])::text[])))
);


ALTER TABLE public.motorcycle_details OWNER TO postgres;

--
-- Name: motorcycle_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.motorcycle_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.motorcycle_details_id_seq OWNER TO postgres;

--
-- Name: motorcycle_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.motorcycle_details_id_seq OWNED BY public.motorcycle_details.id;


--
-- Name: organization_members; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.organization_members (
    id bigint NOT NULL,
    photo character varying(255),
    name character varying(255) NOT NULL,
    "position" character varying(255) NOT NULL,
    profile text,
    sort_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.organization_members OWNER TO postgres;

--
-- Name: organization_members_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.organization_members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.organization_members_id_seq OWNER TO postgres;

--
-- Name: organization_members_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.organization_members_id_seq OWNED BY public.organization_members.id;


--
-- Name: partners; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.partners (
    id bigint NOT NULL,
    logo character varying(255),
    name character varying(255) NOT NULL,
    category character varying(255),
    website_url character varying(255),
    description text,
    sort_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.partners OWNER TO postgres;

--
-- Name: partners_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.partners_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.partners_id_seq OWNER TO postgres;

--
-- Name: partners_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.partners_id_seq OWNED BY public.partners.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: post_images; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.post_images (
    id bigint NOT NULL,
    post_id bigint NOT NULL,
    image character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.post_images OWNER TO postgres;

--
-- Name: post_images_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.post_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.post_images_id_seq OWNER TO postgres;

--
-- Name: post_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.post_images_id_seq OWNED BY public.post_images.id;


--
-- Name: posts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.posts (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    content text NOT NULL,
    image character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    source_name character varying(255),
    source_url text
);


ALTER TABLE public.posts OWNER TO postgres;

--
-- Name: posts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.posts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.posts_id_seq OWNER TO postgres;

--
-- Name: posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.posts_id_seq OWNED BY public.posts.id;


--
-- Name: property_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.property_details (
    id bigint NOT NULL,
    listing_id bigint NOT NULL,
    house_type character varying(255),
    land_area integer,
    building_area integer,
    bedrooms integer,
    bathrooms integer,
    floors integer,
    certificate character varying(255),
    facilities text,
    is_kpr boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.property_details OWNER TO postgres;

--
-- Name: property_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.property_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.property_details_id_seq OWNER TO postgres;

--
-- Name: property_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.property_details_id_seq OWNED BY public.property_details.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: site_visits; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.site_visits (
    id bigint NOT NULL,
    user_id bigint,
    session_id character varying(255),
    ip_address character varying(45),
    user_agent text,
    method character varying(10) DEFAULT 'GET'::character varying NOT NULL,
    path character varying(255),
    url text,
    referer text,
    visited_at timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.site_visits OWNER TO postgres;

--
-- Name: site_visits_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.site_visits_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.site_visits_id_seq OWNER TO postgres;

--
-- Name: site_visits_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.site_visits_id_seq OWNED BY public.site_visits.id;


--
-- Name: testimonials; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.testimonials (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    job character varying(255),
    message text NOT NULL,
    photo character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    rating integer DEFAULT 5 NOT NULL
);


ALTER TABLE public.testimonials OWNER TO postgres;

--
-- Name: testimonials_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.testimonials_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.testimonials_id_seq OWNER TO postgres;

--
-- Name: testimonials_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.testimonials_id_seq OWNED BY public.testimonials.id;


--
-- Name: upgrade_requests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.upgrade_requests (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    no_hp character varying(255) NOT NULL,
    alamat text NOT NULL,
    role character varying(255) NOT NULL,
    nama_agen character varying(255),
    nama_pemilik_agen character varying(255),
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.upgrade_requests OWNER TO postgres;

--
-- Name: upgrade_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.upgrade_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.upgrade_requests_id_seq OWNER TO postgres;

--
-- Name: upgrade_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.upgrade_requests_id_seq OWNED BY public.upgrade_requests.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'user'::character varying NOT NULL,
    status character varying(255) DEFAULT 'normal'::character varying NOT NULL,
    requested_role character varying(255),
    phone character varying(255),
    address text,
    profile_photo character varying(255),
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['user'::character varying, 'admin'::character varying, 'agen'::character varying, 'pemilik'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: agent_requests id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agent_requests ALTER COLUMN id SET DEFAULT nextval('public.agent_requests_id_seq'::regclass);


--
-- Name: car_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.car_details ALTER COLUMN id SET DEFAULT nextval('public.car_details_id_seq'::regclass);


--
-- Name: carousels id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carousels ALTER COLUMN id SET DEFAULT nextval('public.carousels_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: favorites id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favorites ALTER COLUMN id SET DEFAULT nextval('public.favorites_id_seq'::regclass);


--
-- Name: job_applications id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_applications ALTER COLUMN id SET DEFAULT nextval('public.job_applications_id_seq'::regclass);


--
-- Name: job_vacancies id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_vacancies ALTER COLUMN id SET DEFAULT nextval('public.job_vacancies_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: listing_images id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listing_images ALTER COLUMN id SET DEFAULT nextval('public.listing_images_id_seq'::regclass);


--
-- Name: listing_views id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listing_views ALTER COLUMN id SET DEFAULT nextval('public.listing_views_id_seq'::regclass);


--
-- Name: listings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listings ALTER COLUMN id SET DEFAULT nextval('public.listings_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: motorcycle_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.motorcycle_details ALTER COLUMN id SET DEFAULT nextval('public.motorcycle_details_id_seq'::regclass);


--
-- Name: organization_members id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organization_members ALTER COLUMN id SET DEFAULT nextval('public.organization_members_id_seq'::regclass);


--
-- Name: partners id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.partners ALTER COLUMN id SET DEFAULT nextval('public.partners_id_seq'::regclass);


--
-- Name: post_images id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.post_images ALTER COLUMN id SET DEFAULT nextval('public.post_images_id_seq'::regclass);


--
-- Name: posts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts ALTER COLUMN id SET DEFAULT nextval('public.posts_id_seq'::regclass);


--
-- Name: property_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property_details ALTER COLUMN id SET DEFAULT nextval('public.property_details_id_seq'::regclass);


--
-- Name: site_visits id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.site_visits ALTER COLUMN id SET DEFAULT nextval('public.site_visits_id_seq'::regclass);


--
-- Name: testimonials id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.testimonials ALTER COLUMN id SET DEFAULT nextval('public.testimonials_id_seq'::regclass);


--
-- Name: upgrade_requests id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.upgrade_requests ALTER COLUMN id SET DEFAULT nextval('public.upgrade_requests_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: agent_requests; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.agent_requests (id, user_id, role, nama, nama_agen, nama_pemilik_agen, no_hp, email, alamat, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
laravel-cache-wilayah_id.provinces	a:2:{s:4:"data";a:38:{i:0;a:2:{s:4:"code";s:2:"11";s:4:"name";s:4:"Aceh";}i:1;a:2:{s:4:"code";s:2:"51";s:4:"name";s:4:"Bali";}i:2;a:2:{s:4:"code";s:2:"36";s:4:"name";s:6:"Banten";}i:3;a:2:{s:4:"code";s:2:"17";s:4:"name";s:8:"Bengkulu";}i:4;a:2:{s:4:"code";s:2:"34";s:4:"name";s:26:"Daerah Istimewa Yogyakarta";}i:5;a:2:{s:4:"code";s:2:"31";s:4:"name";s:11:"DKI Jakarta";}i:6;a:2:{s:4:"code";s:2:"75";s:4:"name";s:9:"Gorontalo";}i:7;a:2:{s:4:"code";s:2:"15";s:4:"name";s:5:"Jambi";}i:8;a:2:{s:4:"code";s:2:"32";s:4:"name";s:10:"Jawa Barat";}i:9;a:2:{s:4:"code";s:2:"33";s:4:"name";s:11:"Jawa Tengah";}i:10;a:2:{s:4:"code";s:2:"35";s:4:"name";s:10:"Jawa Timur";}i:11;a:2:{s:4:"code";s:2:"61";s:4:"name";s:16:"Kalimantan Barat";}i:12;a:2:{s:4:"code";s:2:"63";s:4:"name";s:18:"Kalimantan Selatan";}i:13;a:2:{s:4:"code";s:2:"62";s:4:"name";s:17:"Kalimantan Tengah";}i:14;a:2:{s:4:"code";s:2:"64";s:4:"name";s:16:"Kalimantan Timur";}i:15;a:2:{s:4:"code";s:2:"65";s:4:"name";s:16:"Kalimantan Utara";}i:16;a:2:{s:4:"code";s:2:"19";s:4:"name";s:25:"Kepulauan Bangka Belitung";}i:17;a:2:{s:4:"code";s:2:"21";s:4:"name";s:14:"Kepulauan Riau";}i:18;a:2:{s:4:"code";s:2:"18";s:4:"name";s:7:"Lampung";}i:19;a:2:{s:4:"code";s:2:"81";s:4:"name";s:6:"Maluku";}i:20;a:2:{s:4:"code";s:2:"82";s:4:"name";s:12:"Maluku Utara";}i:21;a:2:{s:4:"code";s:2:"52";s:4:"name";s:19:"Nusa Tenggara Barat";}i:22;a:2:{s:4:"code";s:2:"53";s:4:"name";s:19:"Nusa Tenggara Timur";}i:23;a:2:{s:4:"code";s:2:"91";s:4:"name";s:5:"Papua";}i:24;a:2:{s:4:"code";s:2:"92";s:4:"name";s:11:"Papua Barat";}i:25;a:2:{s:4:"code";s:2:"96";s:4:"name";s:16:"Papua Barat Daya";}i:26;a:2:{s:4:"code";s:2:"95";s:4:"name";s:16:"Papua Pegunungan";}i:27;a:2:{s:4:"code";s:2:"93";s:4:"name";s:13:"Papua Selatan";}i:28;a:2:{s:4:"code";s:2:"94";s:4:"name";s:12:"Papua Tengah";}i:29;a:2:{s:4:"code";s:2:"14";s:4:"name";s:4:"Riau";}i:30;a:2:{s:4:"code";s:2:"76";s:4:"name";s:14:"Sulawesi Barat";}i:31;a:2:{s:4:"code";s:2:"73";s:4:"name";s:16:"Sulawesi Selatan";}i:32;a:2:{s:4:"code";s:2:"72";s:4:"name";s:15:"Sulawesi Tengah";}i:33;a:2:{s:4:"code";s:2:"74";s:4:"name";s:17:"Sulawesi Tenggara";}i:34;a:2:{s:4:"code";s:2:"71";s:4:"name";s:14:"Sulawesi Utara";}i:35;a:2:{s:4:"code";s:2:"13";s:4:"name";s:14:"Sumatera Barat";}i:36;a:2:{s:4:"code";s:2:"16";s:4:"name";s:16:"Sumatera Selatan";}i:37;a:2:{s:4:"code";s:2:"12";s:4:"name";s:14:"Sumatera Utara";}}s:4:"meta";a:2:{s:25:"administrative_area_level";i:1;s:10:"updated_at";s:10:"2025-07-04";}}	1779080049
laravel-cache-wilayah_id.regencies.35	a:2:{s:4:"data";a:38:{i:0;a:2:{s:4:"code";s:5:"35.26";s:4:"name";s:19:"Kabupaten Bangkalan";}i:1;a:2:{s:4:"code";s:5:"35.10";s:4:"name";s:20:"Kabupaten Banyuwangi";}i:2;a:2:{s:4:"code";s:5:"35.05";s:4:"name";s:16:"Kabupaten Blitar";}i:3;a:2:{s:4:"code";s:5:"35.22";s:4:"name";s:20:"Kabupaten Bojonegoro";}i:4;a:2:{s:4:"code";s:5:"35.11";s:4:"name";s:19:"Kabupaten Bondowoso";}i:5;a:2:{s:4:"code";s:5:"35.25";s:4:"name";s:16:"Kabupaten Gresik";}i:6;a:2:{s:4:"code";s:5:"35.09";s:4:"name";s:16:"Kabupaten Jember";}i:7;a:2:{s:4:"code";s:5:"35.17";s:4:"name";s:17:"Kabupaten Jombang";}i:8;a:2:{s:4:"code";s:5:"35.06";s:4:"name";s:16:"Kabupaten Kediri";}i:9;a:2:{s:4:"code";s:5:"35.24";s:4:"name";s:18:"Kabupaten Lamongan";}i:10;a:2:{s:4:"code";s:5:"35.08";s:4:"name";s:18:"Kabupaten Lumajang";}i:11;a:2:{s:4:"code";s:5:"35.19";s:4:"name";s:16:"Kabupaten Madiun";}i:12;a:2:{s:4:"code";s:5:"35.20";s:4:"name";s:17:"Kabupaten Magetan";}i:13;a:2:{s:4:"code";s:5:"35.07";s:4:"name";s:16:"Kabupaten Malang";}i:14;a:2:{s:4:"code";s:5:"35.16";s:4:"name";s:19:"Kabupaten Mojokerto";}i:15;a:2:{s:4:"code";s:5:"35.18";s:4:"name";s:17:"Kabupaten Nganjuk";}i:16;a:2:{s:4:"code";s:5:"35.21";s:4:"name";s:15:"Kabupaten Ngawi";}i:17;a:2:{s:4:"code";s:5:"35.01";s:4:"name";s:17:"Kabupaten Pacitan";}i:18;a:2:{s:4:"code";s:5:"35.28";s:4:"name";s:19:"Kabupaten Pamekasan";}i:19;a:2:{s:4:"code";s:5:"35.14";s:4:"name";s:18:"Kabupaten Pasuruan";}i:20;a:2:{s:4:"code";s:5:"35.02";s:4:"name";s:18:"Kabupaten Ponorogo";}i:21;a:2:{s:4:"code";s:5:"35.13";s:4:"name";s:21:"Kabupaten Probolinggo";}i:22;a:2:{s:4:"code";s:5:"35.27";s:4:"name";s:17:"Kabupaten Sampang";}i:23;a:2:{s:4:"code";s:5:"35.15";s:4:"name";s:18:"Kabupaten Sidoarjo";}i:24;a:2:{s:4:"code";s:5:"35.12";s:4:"name";s:19:"Kabupaten Situbondo";}i:25;a:2:{s:4:"code";s:5:"35.29";s:4:"name";s:17:"Kabupaten Sumenep";}i:26;a:2:{s:4:"code";s:5:"35.03";s:4:"name";s:20:"Kabupaten Trenggalek";}i:27;a:2:{s:4:"code";s:5:"35.23";s:4:"name";s:15:"Kabupaten Tuban";}i:28;a:2:{s:4:"code";s:5:"35.04";s:4:"name";s:21:"Kabupaten Tulungagung";}i:29;a:2:{s:4:"code";s:5:"35.79";s:4:"name";s:9:"Kota Batu";}i:30;a:2:{s:4:"code";s:5:"35.72";s:4:"name";s:11:"Kota Blitar";}i:31;a:2:{s:4:"code";s:5:"35.71";s:4:"name";s:11:"Kota Kediri";}i:32;a:2:{s:4:"code";s:5:"35.77";s:4:"name";s:11:"Kota Madiun";}i:33;a:2:{s:4:"code";s:5:"35.73";s:4:"name";s:11:"Kota Malang";}i:34;a:2:{s:4:"code";s:5:"35.76";s:4:"name";s:14:"Kota Mojokerto";}i:35;a:2:{s:4:"code";s:5:"35.75";s:4:"name";s:13:"Kota Pasuruan";}i:36;a:2:{s:4:"code";s:5:"35.74";s:4:"name";s:16:"Kota Probolinggo";}i:37;a:2:{s:4:"code";s:5:"35.78";s:4:"name";s:13:"Kota Surabaya";}}s:4:"meta";a:2:{s:25:"administrative_area_level";i:2;s:10:"updated_at";s:10:"2025-07-04";}}	1779080117
laravel-cache-wilayah_id.districts.35.04	a:2:{s:4:"data";a:19:{i:0;a:2:{s:4:"code";s:8:"35.04.17";s:4:"name";s:7:"Bandung";}i:1;a:2:{s:4:"code";s:8:"35.04.15";s:4:"name";s:6:"Besuki";}i:2;a:2:{s:4:"code";s:8:"35.04.02";s:4:"name";s:9:"Boyolangu";}i:3;a:2:{s:4:"code";s:8:"35.04.16";s:4:"name";s:11:"Campurdarat";}i:4;a:2:{s:4:"code";s:8:"35.04.09";s:4:"name";s:7:"Gondang";}i:5;a:2:{s:4:"code";s:8:"35.04.14";s:4:"name";s:9:"Kalidawir";}i:6;a:2:{s:4:"code";s:8:"35.04.08";s:4:"name";s:10:"Karangrejo";}i:7;a:2:{s:4:"code";s:8:"35.04.05";s:4:"name";s:6:"Kauman";}i:8;a:2:{s:4:"code";s:8:"35.04.03";s:4:"name";s:10:"Kedungwaru";}i:9;a:2:{s:4:"code";s:8:"35.04.04";s:4:"name";s:7:"Ngantru";}i:10;a:2:{s:4:"code";s:8:"35.04.11";s:4:"name";s:6:"Ngunut";}i:11;a:2:{s:4:"code";s:8:"35.04.06";s:4:"name";s:9:"Pagerwojo";}i:12;a:2:{s:4:"code";s:8:"35.04.18";s:4:"name";s:5:"Pakel";}i:13;a:2:{s:4:"code";s:8:"35.04.12";s:4:"name";s:11:"Pucanglaban";}i:14;a:2:{s:4:"code";s:8:"35.04.13";s:4:"name";s:10:"Rejotangan";}i:15;a:2:{s:4:"code";s:8:"35.04.07";s:4:"name";s:7:"Sendang";}i:16;a:2:{s:4:"code";s:8:"35.04.10";s:4:"name";s:12:"Sumbergempol";}i:17;a:2:{s:4:"code";s:8:"35.04.19";s:4:"name";s:14:"Tanggunggunung";}i:18;a:2:{s:4:"code";s:8:"35.04.01";s:4:"name";s:11:"Tulungagung";}}s:4:"meta";a:2:{s:25:"administrative_area_level";i:3;s:10:"updated_at";s:10:"2025-07-04";}}	1779080122
laravel-cache-wilayah_id.villages.35.04.01	a:2:{s:4:"data";a:14:{i:0;a:2:{s:4:"code";s:13:"35.04.01.1006";s:4:"name";s:4:"Bago";}i:1;a:2:{s:4:"code";s:13:"35.04.01.1014";s:4:"name";s:7:"Botoran";}i:2;a:2:{s:4:"code";s:13:"35.04.01.1005";s:4:"name";s:5:"Jepun";}i:3;a:2:{s:4:"code";s:13:"35.04.01.1009";s:4:"name";s:12:"Kampungdalem";}i:4;a:2:{s:4:"code";s:13:"35.04.01.1003";s:4:"name";s:10:"Karangwaru";}i:5;a:2:{s:4:"code";s:13:"35.04.01.1010";s:4:"name";s:6:"Kauman";}i:6;a:2:{s:4:"code";s:13:"35.04.01.1001";s:4:"name";s:10:"Kedungsoko";}i:7;a:2:{s:4:"code";s:13:"35.04.01.1008";s:4:"name";s:7:"Kenayan";}i:8;a:2:{s:4:"code";s:13:"35.04.01.1007";s:4:"name";s:9:"Kepatihan";}i:9;a:2:{s:4:"code";s:13:"35.04.01.1011";s:4:"name";s:9:"Kutoanyar";}i:10;a:2:{s:4:"code";s:13:"35.04.01.1013";s:4:"name";s:12:"Panggungrejo";}i:11;a:2:{s:4:"code";s:13:"35.04.01.1012";s:4:"name";s:7:"Sembung";}i:12;a:2:{s:4:"code";s:13:"35.04.01.1004";s:4:"name";s:7:"Tamanan";}i:13;a:2:{s:4:"code";s:13:"35.04.01.1002";s:4:"name";s:6:"Tertek";}}s:4:"meta";a:2:{s:25:"administrative_area_level";i:4;s:10:"updated_at";s:10:"2025-07-04";}}	1779080127
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: car_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.car_details (id, listing_id, brand, model, year, engine, transmission, created_at, updated_at, fuel_type, color, kilometer) FROM stdin;
\.


--
-- Data for Name: carousels; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.carousels (id, image, title, created_at, updated_at, link_url, placement, page_key, label, text, sort_order, is_active, label_color, title_color, text_color, buttons) FROM stdin;
3	carousel/SHM3KHPiFNWLSftD47z9Z7X2cwm1ACwFCF24XybJ.jpg	\N	2026-05-01 08:13:32	2026-05-01 08:13:32	\N	content	\N	\N	\N	0	t	\N	\N	\N	\N
4	carousel/RN5JJ5M9NOVzMw48fCyoOM8uKTjMsMii46JUxi3s.jpg	\N	2026-05-01 08:14:58	2026-05-01 08:14:58	\N	content	\N	\N	\N	0	t	\N	\N	\N	\N
5	carousel/mlGC05P0xR9e67ESDVDb9k7h5pwbA97dIUAVKVsI.jpg	\N	2026-05-01 08:15:07	2026-05-01 08:15:07	\N	content	\N	\N	\N	0	t	\N	\N	\N	\N
6	carousel/IyRsHw6woHhxVNSOthjBxN1RV3Ug8IfWafmk2hnP.jpg	\N	2026-05-01 08:15:17	2026-05-01 08:15:17	\N	content	\N	\N	\N	0	t	\N	\N	\N	\N
8	hero-carousel/xUMAR0PwAw54OIyaIOth6mljnSol3Qo2ssxAcMVj.png	Temukan Rumah & Tanah Berkualitas, Nyaman & Terjangkau	2026-05-09 09:56:35	2026-05-09 09:59:32	\N	hero	properti	Properti Tulungagung	Properti Pilihan di Tulungagung dan Sekitarnya dengan lokasi strategis, lingkungan nyaman, dan harga terbaik untuk anda	1	t	#ffffff	#faf200	#ffffff	\N
7	hero-carousel/3B5tTUTUClW5VAObW5QCKiANH2TqyQdkHoC76wO6.png	Bantu Anda Menemukan Rumah Yang Tepat.	2026-05-09 09:49:18	2026-05-09 15:48:55	\N	hero	home	Properti Terpadu	Setiap Listing disusun agar harga, lokasi, sertifikat, dan detail utama mudah dipahami	2	t	#000000	#ffffff	#000000	\N
9	hero-carousel/5qfYnK15swDBXGYLebiCqoJBkR3HpTPcFjMwuf1Y.png	Gabung Jadi Mitra Arsantara dan Pasarkan Produk Lebih Mudah	2026-05-10 16:59:26	2026-05-10 16:59:26	\N	hero	ads.guide	Pasang Iklan	Pasarkan properti, tanah, mobil, dan motor dengan sistem listing profesional dan didampingi tim admin	0	t	#295794	#3529e0	#000000	\N
10	hero-carousel/UYHoFyIsnBYurMouCTF99rvmJA9VVS91NdTy577v.png	Mulai jadi mitra Arsantara Sekarang	2026-05-10 17:07:09	2026-05-10 17:07:09	\N	hero	ads.guide	Pasang Iklan	Dapatkan pengalaman jualan yang lebih mudah, produk cepat tayang, dan hasil lebih maksimal bersama Arsantara Management	1	t	#0b2b56	#211a56	#000000	\N
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categories (id, name, slug, created_at, updated_at, is_active) FROM stdin;
10	Ruko	ruko	2026-05-12 21:44:51	2026-05-12 21:44:51	t
11	Perkantoran	perkantoran	2026-05-12 21:44:51	2026-05-12 21:44:51	t
12	Gudang	gudang	2026-05-12 21:44:51	2026-05-12 21:44:51	t
13	Kios	kios	2026-05-12 21:44:51	2026-05-12 21:44:51	t
14	Truk & Kendaraan Komersil	truk-kendaraan-komersil	2026-05-12 21:44:52	2026-05-12 21:44:52	t
15	Rumah	rumah	2026-05-13 00:31:10	2026-05-13 00:31:10	t
16	Tanah	tanah	2026-05-13 00:31:10	2026-05-13 00:31:10	t
17	Mobil	mobil	2026-05-13 00:31:10	2026-05-13 00:31:10	t
18	Motor	motor	2026-05-13 00:31:10	2026-05-13 00:31:10	t
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: favorites; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.favorites (id, user_id, listing_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: job_applications; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_applications (id, job_vacancy_id, full_name, email, nik, gender, birth_date, source, phone, domicile_address, province, city, district, village, expected_salary, cv_path, cv_original_name, education_level, education_institution, major, gpa, work_experience, statement_accepted, privacy_accepted, created_at, updated_at, status, reviewed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: job_vacancies; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_vacancies (id, title, employment_type, location, deadline, description, requirements, apply_url, sort_order, is_active, created_at, updated_at) FROM stdin;
1	Marketing	Staff	Tulungagung	2026-06-30	Menjaga, membina dan membangun relasi dengan partner bank, agen, showroom ataupun dealer untuk mendapatkan order dan meningkatkan penjualan produk dalam mencapai target yang telah ditentukan\r\nMelakukan survey nasabah untuk menganalisa kelayakan kredit dan pengumpulan kelengkapan berkas nasabah\r\nMembuat laporan survey dan melaporkan hasil survey tersebut kepada Marketing Head, Credit Analyst dan Staff Administrasi yang ditunjuk untuk penginputan data nasabah\r\nBerkoordinasi dengan pihak partner bank, agen, showroom ataupun dealer terkait hasil analisa kelayakan nasabah.\r\nMenjaga komunikasi dengan nasabah agar pembayaran tidak tertunggak.	Minimal lulusan D3 semua jurusan\r\nMemiliki pengalaman min. 1 tahun sebagai marketing staff diperusahaan multifinance, fresh graduate dipersilahkan melamar.\r\nMenyukai tantangan dan pekerjaan dengan target\r\nMemiliki kemampuan komunikasi yang baik\r\nMemiliki kemampuan interpersonal serta intrapersonal yang baik\r\nMampu mengoperasikan aplikasi office (Excel, Word, Powerpoint)\r\nTidak pernah berurusan dengan permasalahan hukum baik pidana/perdata	\N	0	t	2026-05-11 04:28:52	2026-05-11 04:28:52
2	Stakeholder Management (Staff)	Full Time	Tulungagung	2026-12-02	Membantu menyiapkan jadwal rapat bulanan dan tahunan Dewan Komisaris termasuk Komite dibawah supervisi Dewan Komisaris\r\n\r\nPencatatan notulen dan summary rapat Dekom termasuk Komite dibawah supervisi Dewan Komisaris sampai dengan approval Dewan Komisaris\r\n\r\nMembantu menyampaikan tindak lanjut summary rapat kepada Divisi terkait, pengumpulan tindak lanjut rapat tepat waktu, validasi tindak lanjut dari divisi terkait terhadap arahan dari Dewan Komisaris atau Komite dibawah supervisi Dewan Komisaris dan Dewan Pengawas Syariah, termasuk menyusun tindak lanjut final dalam format pptx\r\n\r\nMembantu Pihak Independen menyusun Laporan Pengawasan Dewan Komisaris dan Dewan Pengawas Syariah setiap semester\r\n\r\nMembantu menyusun surat keluar yang berkaitan dengan kebutuhan Dewan Komisaris dan Komite dibawah supervisi Dewan Komisaris\r\n\r\nMembantu menyiapkan kebutuhan Dewan Komisaris lainnya seperti (cuti, obat – obatan, bunga papan, transport, dan kebutuhan yang di request secara langsung) oleh Dewan Komisaris\r\n\r\nMembantu filling dokumen (surat, proposal, laporan) yang berkaitan dengan Dewan Komisaris termasuk Komite dibawah supervisi Dewan Komisaris\r\n\r\nMembantu menyiapkan kebutuhan kunjungan untuk Uji Petik Dewan Pengawas Syariah maupun kunjungan Dewan Komisaris ke Cabang\r\n\r\nPendampingan Dewan Komisaris dan Dewan Pengawas Syariah pada event, baik internal maupun eksternal	Pendidikan min. S1 semua jurusan (diutamakan jurusan hukum)\r\n\r\nPengalaman min. 1 tahun dibidang yang sama, Fresh Graduate dipersilahkan melamar\r\n\r\nMemiliki pengalaman sebagai notulen dalam meeting\r\n\r\nDapat bekerjasama dengan tim\r\n\r\nMemiliki kemampuan komunikasi yang baik\r\n\r\nMemiliki kemampuan di bidang administratif\r\n\r\nMemiliki pengalaman dalam mengoperasikan Ms Office (Word, Excel, Powerpoint)\r\n\r\nBersedia melakukan kunjungan keluar kota	\N	1	t	2026-05-11 05:20:25	2026-05-11 05:20:25
3	ACCOUNT RECEIVABLE OFFICER (Staff)	Full Time	Tulungagung	2026-12-20	Menjalin komunikasi dengan konsumen terkait tunggakan atau kendala pembayaran yang terjadi pada konsumen tersebut.\r\nBernegosiasi dengan memberikan surat peringatan keterlambatan, membuat kesepakatan janji bayar terhadap konsumen yang menunggak\r\nMelakukan penawaran solusi seperti sistem autodebet atau perubahan tanggal jatuh tempo pembayaran untuk menjadi solusi keuangan agar tidak menunggak.\r\nMelakukan penarikan unit dengan tetap mengedepankan komunikasi dengan konsumen yang telah diarahkan oleh collection supervisor\r\nMembuat laporan terkait hasil kunjungan dan penanganan konsumen yang menunggak dan melaporkannya kepada collection supervisor\r\nTetap menjaga nama baik perusahaan ketika menjalankan proses kunjungan	Minimal lulusan SMA semua jurusan\r\nMemiliki pengalaman min. 1 tahun sebagai collector staff diperusahaan multifinance, fresh graduate dipersilahkan melamar.\r\nMenyukai tantangan dan pekerjaan dengan target\r\nMemiliki kemampuan komunikasi yang baik\r\nMemiliki kemampuan interpersonal serta intrapersonal yang baik\r\nMampu mengoperasikan aplikasi office (Excel, Word, Powerpoint)\r\nTidak pernah berurusan dengan permasalahan hukum baik pidana/perdata	\N	3	t	2026-05-11 05:22:26	2026-05-11 05:22:26
4	Collection Staff/ Field Collection Staff/ Staf Penagihan/ Staf Penagihan Lapangan (ARO/REMOFF) (Staff)	Full Time	Tulungagung	2026-12-23	Menjalin komunikasi dengan konsumen terkait tunggakan atau kendala pembayaran yang terjadi pada konsumen tersebut.\r\nBernegosiasi dengan memberikan surat peringatan keterlambatan, membuat kesepakatan janji bayar terhadap konsumen yang menunggak\r\nMelakukan penawaran solusi seperti sistem autodebet atau perubahan tanggal jatuh tempo pembayaran untuk menjadi solusi keuangan agar tidak menunggak.\r\nMelakukan penarikan unit dengan tetap mengedepankan komunikasi dengan konsumen yang telah diarahkan oleh collection supervisor\r\nMembuat laporan terkait hasil kunjungan dan penanganan konsumen yang menunggak dan melaporkannya kepada collection supervisor\r\nTetap menjaga nama baik perusahaan ketika menjalankan proses kunjungan	Minimal lulusan SMA semua jurusan\r\nMemiliki pengalaman min. 1 tahun sebagai collector staff diperusahaan multifinance, fresh graduate dipersilahkan melamar.\r\nMenyukai tantangan dan pekerjaan dengan target\r\nMemiliki kemampuan komunikasi yang baik\r\nMemiliki kemampuan interpersonal serta intrapersonal yang baik\r\nMampu mengoperasikan aplikasi office (Excel, Word, Powerpoint)\r\nTidak pernah berurusan dengan permasalahan hukum baik pidana/perdata	\N	3	t	2026-05-11 05:27:08	2026-05-11 05:27:08
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: listing_images; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.listing_images (id, listing_id, image, created_at, updated_at) FROM stdin;
26	9	listings/5NmrrOmogR9mKKYcGLSNbpirn9iGCfTnFWuWHgcX.png	2026-05-13 00:35:13	2026-05-13 00:35:13
27	10	listings/dCu3S7l3lATwY0eEFMX70d8gT73m1ZPCtFzNndNd.png	2026-05-13 00:40:03	2026-05-13 00:40:03
28	10	listings/ZzLJMYKCWmLLomyD8mFbwSoj48zgPr1mnFEObQlR.png	2026-05-13 00:40:05	2026-05-13 00:40:05
29	10	listings/6szJRl05dwKoJIQYwntOMU9IsVHAzIiPLY8mjbET.png	2026-05-13 00:40:06	2026-05-13 00:40:06
30	10	listings/0VkCLoRYWnz01cavXmoLIJTwdrPMIBxi7yyzHWLg.png	2026-05-13 00:40:07	2026-05-13 00:40:07
31	10	listings/SX0JesJIQWWYsybzF2U3zpCe3D9edM5KZ3N54dVA.png	2026-05-13 00:40:08	2026-05-13 00:40:08
32	11	listings/SK05EalDAGauTeVMoQHTCE0nnSuFuH0wOQhWBSTJ.png	2026-05-13 00:47:41	2026-05-13 00:47:41
33	11	listings/EIGPBgRXvimVgzXCfQOGiQGns7GirMEfpESy21oC.png	2026-05-13 00:47:42	2026-05-13 00:47:42
34	11	listings/aEik2orgEKEHjNYBnwtpEo9OhaDMwZUxPIRfraNy.png	2026-05-13 00:47:43	2026-05-13 00:47:43
35	11	listings/iULP6vDjdmwg6kYFbLBMxHqpzNFZqBoAvAscmutu.png	2026-05-13 00:47:44	2026-05-13 00:47:44
36	11	listings/xkLnQh3OdRazHE4Y9WCmMuYRG21Mc43XlL6Gs3hd.png	2026-05-13 00:47:45	2026-05-13 00:47:45
37	11	listings/8jgskvxsW5svNQcziAzi0aC5zoFY7gf7lsCfRieK.png	2026-05-13 00:47:46	2026-05-13 00:47:46
38	11	listings/LFKANRVGTpGLBJzxg3oaX4GLGQJxe6ac8yEmrSHP.png	2026-05-13 00:47:47	2026-05-13 00:47:47
39	11	listings/tfrNonKjiLmpMOD8CPVJar3jWdIVZK53LByjbWPI.png	2026-05-13 00:47:48	2026-05-13 00:47:48
40	12	listings/l0HQmpJpqc34goXhgjAsHiHIu3OAKN2YImnb9B0f.jpg	2026-05-13 00:54:05	2026-05-13 00:54:05
41	12	listings/Fo3XYNxqXvloeitESKCJ0PUvELgVbYh9aQ6Et8Wm.jpg	2026-05-13 00:54:05	2026-05-13 00:54:05
42	12	listings/y31p6csXFJdzwg2bTDijQ4CUvjW8ozFMAEFmBYCu.jpg	2026-05-13 00:54:05	2026-05-13 00:54:05
43	12	listings/dTJVht4ujBz4Pl340A5dDoqs9ffmiisuZksEUtRn.jpg	2026-05-13 00:54:06	2026-05-13 00:54:06
44	13	listings/5JoO7KlKjWQoii51NimbrcvV8l4h3yK1jZ3cEhOh.png	2026-05-13 00:58:14	2026-05-13 00:58:14
45	13	listings/bKM2rjlztM7jYeFQUpNgl8R4UOUQw5IZCGYcJxQT.png	2026-05-13 00:58:15	2026-05-13 00:58:15
46	13	listings/qxAViZSQkjbJtKLorvFUmNsDtNMyZhAroT1GYzom.png	2026-05-13 00:58:15	2026-05-13 00:58:15
47	13	listings/9UPoECO9vESQraSMAdmhEcTZJFGsV5ILqlrw0k8l.png	2026-05-13 00:58:16	2026-05-13 00:58:16
48	14	listings/EZEiIbG8vmjFN4DTbyY8e9bHFq6B2oAJHvpSUOlw.png	2026-05-13 03:49:58	2026-05-13 03:49:58
49	14	listings/MffLpgYPO2Vu8NFY2n6RJkTbqfUiNWTVG8rNswZG.png	2026-05-13 03:50:01	2026-05-13 03:50:01
50	14	listings/RB9TtfBl3WAETFKAKsnBrnp0kTcP8Z4IYR8JMipB.png	2026-05-13 03:50:04	2026-05-13 03:50:04
51	14	listings/Qi4NfdoDqzun2uWISbU5y0rZgaGX7fkVnoL6lvoW.png	2026-05-13 03:50:06	2026-05-13 03:50:06
52	14	listings/7UH97BXZLhJBpALjNxfhEmlIv8LgwpUaWikxbnng.png	2026-05-13 03:50:08	2026-05-13 03:50:08
53	14	listings/B8cLXkXkjWWG1x5ryjjRiiQr4ObuJOchlS3CfsDT.png	2026-05-13 03:50:11	2026-05-13 03:50:11
54	14	listings/N7jJFIgQiVaBk4t0taASwoflGGikSCWX8t359StM.png	2026-05-13 03:50:13	2026-05-13 03:50:13
55	14	listings/uOfuSUTFiOIN5TolCROq2fXlVkX0JM3hGVGA9Gtg.png	2026-05-13 03:50:15	2026-05-13 03:50:15
56	15	listings/q1C4aMilD26XpuOM1fFTO8n5aA4QLXS6LmZv1lX0.png	2026-05-13 03:58:58	2026-05-13 03:58:58
57	15	listings/0qnrHVvufBR1mrYqdAdwT1Iqv5DDib7ubKrCsjle.png	2026-05-13 03:59:02	2026-05-13 03:59:02
58	15	listings/HXz3h5x5mAwv2VajwW3VZ9gzmsZBlV6iESZTuou5.png	2026-05-13 03:59:04	2026-05-13 03:59:04
59	15	listings/fg7vHmTYUNswIuXPTOWGuG0nCJlsET8n15HutJHK.png	2026-05-13 03:59:07	2026-05-13 03:59:07
60	15	listings/f0UpEU6NvjVqePCdKcrpaFa4LKA6SDHPi7S5CC3o.png	2026-05-13 03:59:09	2026-05-13 03:59:09
61	15	listings/IhmZzgvXbLOWDwRccNeoRgw3ZOfUYCvBb3Kdhvyb.png	2026-05-13 03:59:12	2026-05-13 03:59:12
62	15	listings/xzYv2dLYeFfvtncrrAakOU5d9bZqaqNllYgMzVND.png	2026-05-13 03:59:14	2026-05-13 03:59:14
\.


--
-- Data for Name: listing_views; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.listing_views (id, listing_id, user_id, session_id, ip_address, user_agent, viewed_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: listings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.listings (id, user_id, category_id, title, description, price, location, condition, status, is_featured, created_at, updated_at, discount_price, product_code) FROM stdin;
9	10	15	Perumahan	Graha Irfai Tulungagung\r\nIrfai Group Untuk Tulungagung\r\nLokasi Ketanon dan Kauman\r\nHarga 166 Juta\r\nType 36/60\r\nSHM PBG\r\nUtj 1 Jt\r\nDP 6 juta\r\nBiaya Kpr 3 Jt\r\nBelum Termasuk Pajak SHM ,BBn , Notaris	166000000	Kedungwaru, Tulungagung, Jawa Timur	bekas	aktif	f	2026-05-13 00:35:11	2026-05-13 00:35:11	\N	RMH-000009
10	10	12	Gudang Kosong	Spesifikasi :\r\nLegalitas : HGB\r\nLuas tanah : 10.305 m2\r\nLuas bangunan :4.334 m2\r\nBangunan 1 lantai\r\nKamar Tidur : 1\r\nKamar Mandi : 2\r\nGarasi : 1\r\nCarport : 1\r\nPLN : 1300W\r\nHadap : Selatan	800000000	Gudang di Karangrejo Tulungagung Dekat Pondok Pesantren Keedunglo 5 & Lapangan Karangrejo	bekas	aktif	f	2026-05-13 00:40:02	2026-05-13 00:40:02	\N	GDG-000010
11	10	15	Rumah Induk	Rumah Induk\r\nLuas tanah : 829 M2 atau 59 Ru\r\nLebar depan 32 meter\r\nHadap Barat\r\nAkses jalan aspal 2 mobil salipan\r\n3 Kamar tidur\r\n2 kamar mandi\r\nRuang Tamu\r\nRuang keluarga\r\nDapur\r\nGarasi\r\nLuas bangunan : 150 m2\r\n7 kamar tidur + kamar mandi dalam\r\nDapur bersama\r\nCarpot sangat lega\r\nPLN : 2200\r\nAir : sumur dan PDAM	2600000000	Jl. Mayor Sujadi Kedungwaru, Tulungagung Kab., Jawa Timur	bekas	aktif	f	2026-05-13 00:47:40	2026-05-13 00:47:40	\N	RMH-000011
12	10	18	Honda CBR 2015 150R	Harga 9.xxxx nego\r\nMati plat 2025\r\nPajak 2022\r\nPlat L tangan pertama\r\n\r\nKnalpot 2\r\nVisor 2\r\nYang lainya sesuai difoto\r\nKondisi minus pemakaian harian aja \r\nLainya aman	9000000	Kedungwaru, Tulungagung, Jawa Timur	bekas	aktif	f	2026-05-13 00:54:05	2026-05-13 00:54:05	8500000	MTR-000012
13	10	15	Rumah Skandinavian	Dijual cepat rumah model skandinavian.\r\nLokasi dekat dengan RS Iskak, hanya 2 menit dari pusat kota.\r\nDesa Rejoagung, Kecamatan Kedungwaru, Kabupaten Tulungagung	400000000	Kedungwaru, Tulungagung, Jawa Timur	bekas	aktif	f	2026-05-13 00:58:13	2026-05-13 00:58:13	0	RMH-000013
14	10	15	Rumah Murah	Rumah 2 Lantai Pahlawan Tulungagung\r\nLt.204 m (6x34)\r\nLb.300 m\r\n4kt+1 ; 1km+1\r\nSHM Hadap Selatan\r\n700 jt\r\nMore info,call :\r\nFelix Suw\r\nBrighton Merr	700000000	Kedungwaru, Tulungagung, Jawa Timur	bekas	aktif	f	2026-05-13 03:49:54	2026-05-13 03:49:54	0	RMH-000014
15	10	12	Ruko Gudang	Aset Bannk, Bisa KPR	4500000000	Kedungwaru, Tulungagung, Jawa Timur	baru	aktif	f	2026-05-13 03:58:55	2026-05-13 03:58:55	0	GDG-000015
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_03_28_151552_create_categories_table	1
5	2026_03_28_151557_create_listings_table	1
6	2026_03_28_151603_create_listing_images_table	1
7	2026_03_28_151610_create_property_details_table	1
8	2026_03_28_151614_create_car_details_table	1
9	2026_03_28_151619_create_motorcycle_details_table	1
10	2026_03_28_151623_create_favorites_table	1
11	2026_03_29_072314_add_role_to_users_table	1
12	2026_04_12_095227_create_carousels_table	1
13	2026_04_18_212804_create_posts_table	1
14	2026_04_18_212812_create_testimonials_table	1
15	2026_04_18_222702_create_post_images_table	1
16	2026_04_20_081310_add_source_to_posts_table	1
17	2026_04_20_100614_add_rating_to_testimonials_table	1
18	2026_04_24_070551_add_fuel_type_to_car_details_table	1
19	2026_04_24_072735_add_fields_to_car_details_table	1
20	2026_04_24_073346_add_kilometer_to_car_details_table	1
21	2026_04_24_085836_add_kpr_to_property_details_table	1
22	2026_04_29_134658_create_agent_requests_table	1
23	2026_04_29_153202_create_upgrade_requests_table	1
24	2026_04_29_154401_add_status_to_users	1
25	2026_04_29_173349_add_requested_role_to_users	1
26	2026_04_30_000001_add_discount_price_to_listings_table	1
27	2026_04_30_000002_create_site_visits_table	1
28	2026_04_30_000003_create_listing_views_table	1
29	2026_04_30_000004_add_profile_details_to_users_table	1
30	2026_05_01_000004_add_link_url_to_carousels_table	1
31	2026_05_01_000005_add_product_code_to_listings_table	1
32	2026_05_01_000006_add_is_active_to_categories_table	1
33	2026_05_01_000007_create_organization_members_table	1
34	2026_05_01_000008_create_partners_table	1
41	2026_05_09_000001_add_commercial_property_categories	2
42	2026_05_09_000002_add_hero_fields_to_carousels_table	2
43	2026_05_09_000003_add_hero_text_colors_to_carousels_table	2
44	2026_05_10_000001_add_commercial_vehicle_category	2
45	2026_05_11_000001_add_hero_buttons_to_carousels_table	2
46	2026_05_11_000002_create_job_vacancies_table	3
47	2026_05_11_000003_create_job_applications_table	3
48	2026_05_11_000004_add_status_to_job_applications_table	3
\.


--
-- Data for Name: motorcycle_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.motorcycle_details (id, listing_id, brand, model, year, engine, transmission, created_at, updated_at) FROM stdin;
1	12	Honda	CBR 2015 150R	2022	150	manual	2026-05-13 00:54:05	2026-05-13 00:54:05
\.


--
-- Data for Name: organization_members; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.organization_members (id, photo, name, "position", profile, sort_order, is_active, created_at, updated_at) FROM stdin;
1	organization/9PG424vMG8P1IMbS2HVIDnPtn848t1zIbfCu0DtZ.jpg	Pangeran Jendral Rizal Efendi, S. Teh, M. Nuk.	CEO	Orang yang sangat baik, tidak sombong dan rajin sholat	0	t	2026-05-01 09:43:20	2026-05-01 09:43:20
2	organization/Wy9pWvBAsPXByZ3mb2JYjAoA97x3cgEQHojrNCGo.jpg	Dr. H. Rizal Efendi, S. Tes, M. Ger	Head Office	Pencetus dan Pendiri Arsantara	2	t	2026-05-01 09:45:12	2026-05-01 09:45:12
3	organization/z3F3h22H97Bc0tc19iROGy64OKe8HwtP84qsfTCm.png	Pak Narto	Penguasa 7 Elemen	Menjadi Hookage adalah jalan ninjaku	3	t	2026-05-01 09:46:19	2026-05-01 09:46:19
\.


--
-- Data for Name: partners; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.partners (id, logo, name, category, website_url, description, sort_order, is_active, created_at, updated_at) FROM stdin;
1	partners/C88RBNvSoVwCPNaq7CcFMgIyg6WhTdnEDpkT59nu.jpg	Mandiri	\N	\N	\N	0	t	2026-05-01 09:51:11	2026-05-01 09:51:11
2	partners/452ifnINX3C9sWU7oBNfgu5x0KOL03azbK876eSr.jpg	BRI	\N	\N	\N	0	t	2026-05-01 09:51:21	2026-05-01 09:51:21
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: post_images; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.post_images (id, post_id, image, created_at, updated_at) FROM stdin;
1	1	posts/v7vu2zJOFBXIXqMMdXNCUSXbEjzWhzc8z72wfX2v.jpg	2026-04-28 07:18:03	2026-04-28 07:18:03
\.


--
-- Data for Name: posts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.posts (id, title, content, image, created_at, updated_at, source_name, source_url) FROM stdin;
1	Warna Keramik Lantai Teras yang Bagus untuk Hunian Ideal	Penulis: Nurin\r\n\r\nArea depan rumah merupakan elemen pertama yang menyambut kedatangan siapa saja. Impresi awal sebuah hunian sangat dipengaruhi oleh tampilan fasad, dan salah satu komponen paling krusial dalam membentuk visual tersebut adalah permukaan lantainya. Memahami cara menentukan warna keramik lantai teras yang bagus bukan sekadar urusan estetika semata, melainkan juga strategi cerdas untuk meningkatkan kenyamanan serta daya tarik properti secara keseluruhan. Bagi Anda yang sedang merencanakan pembangunan atau renovasi, memperhatikan detail pada area ini akan memberikan dampak signifikan terhadap nuansa rumah.\r\n\r\nSebagai langkah awal untuk mencari berbagai referensi properti dan inspirasi hunian idaman, Anda dapat mengunjungi situs resmi agen properti terpercaya melalui tautan Brighton Real Estate. Di sana, terdapat beragam pilihan hunian yang bisa menjadi tolak ukur dalam menentukan desain fasad yang proporsional. Selain itu, memperkaya wawasan seputar dunia arsitektur dan properti juga sangat penting. Anda bisa menemukan panduan lengkap lainnya dengan mengakses kumpulan artikel properti edukatif yang dirancang khusus untuk membantu pemilik rumah membuat keputusan terbaik.\r\n\r\nRekomendasi Warna Keramik Lantai Teras yang Bagus untuk Berbagai Konsep\r\n\r\nSetiap hunian memiliki karakter unik yang ingin ditonjolkan oleh pemiliknya. Pemilihan rona pada bagian bawah fasad akan mengikat seluruh elemen arsitektur menjadi satu kesatuan yang harmonis. Berikut adalah analisis mendalam mengenai opsi rona yang paling diminati dan terbukti mampu mengangkat derajat visual sebuah bangunan.\r\n\r\nNuansa Abu-abu untuk Kesan Industrial dan Modern\r\nRona abu-abu selalu berhasil menghadirkan nuansa maskulin, bersih, dan kontemporer. Warna ini sangat adaptif dan mudah dipadukan dengan berbagai elemen eksterior lainnya, seperti dinding bata ekspos, baja ringan, maupun tanaman hias tropis. Keunggulan utama rona abu-abu adalah kemampuannya menyamarkan debu atau kotoran ringan, sehingga area depan selalu terlihat rapi meskipun belum sempat dibersihkan. Untuk panduan spesifik mengenai gaya bangunan kekinian, Anda dapat membaca ulasan tentang pilihan lantai eksterior rumah minimalis yang memberikan wawasan lebih detail.\r\n\r\nKehangatan Earth Tone dan Cokelat Alami\r\nBagi hunian yang mengedepankan suasana tropis dan menyatu dengan alam, rona kecokelatan yang menyerupai kayu atau tanah liat adalah pilihan sempurna. Rona ini memberikan efek psikologis yang menenangkan dan menyambut dengan hangat. Penggunaan tekstur urat kayu pada material porselen kini sangat populer karena menggabungkan keindahan visual kayu asli dengan ketangguhan material pabrikan yang tahan rayap dan cuaca. Eksplorasi lebih lanjut mengenai opsi elegan ini bisa ditemukan pada panduan warna lantai yang stylish dan elegan.\r\n\r\nElegansi Hitam Solid untuk Fasad Mewah\r\nPenggunaan rona hitam pada bagian depan rumah memang membutuhkan keberanian, namun hasil yang didapatkan sangat sepadan. Hitam memancarkan aura kemewahan, ketegasan, dan eksklusivitas tingkat tinggi. Hunian premium di kawasan elit sering kali memanfaatkan granit hitam dengan sentuhan akhir matte untuk menciptakan kontras yang dramatis dengan cat dinding berwarna terang. Rona ini sangat efektif menonjolkan garis-garis arsitektur bangunan secara tegas.\r\n\r\nEksplorasi Pola dan Tekstur Permukaan\r\nSelain rona polos, kehadiran pola atau motif tertentu dapat menjadi pusat perhatian yang memikat. Konsep hunian retro atau klasik kontemporer sangat cocok menggunakan pola geometris atau tegel kunci yang memberikan sentuhan nostalgia namun tetap relevan dengan tren masa kini. Pola-pola ini dapat diaplikasikan pada seluruh area atau hanya sebagai aksen pembatas di bagian tengah.\r\n\r\nPenting untuk menyeimbangkan antara keramaian pola dengan elemen fasad lainnya. Jika dinding luar sudah memiliki banyak ornamen, sebaiknya pilih pola lantai yang lebih sederhana. Sebaliknya, dinding yang polos dan simpel bisa dihidupkan dengan lantai bermotif atraktif. Untuk mendapatkan ide visual yang kaya, pastikan Anda menelusuri inspirasi motif lantai depan rumah terbaru yang sedang menjadi tren di kalangan arsitek interior dan eksterior.\r\n\r\nTips Menyelaraskan Rona Eksterior Bangunan\r\nMencapai keharmonisan visual memerlukan ketelitian dalam mencocokkan rona alas dengan cat dinding, warna atap, dan kusen jendela. Pendekatan monokromatik melibatkan penggunaan gradasi dari satu warna yang sama, menciptakan tampilan yang mulus dan luas. Pendekatan kontras, di sisi lain, menggunakan rona yang berseberangan untuk menonjolkan batas area dan memberikan dinamika visual yang berani.\r\n\r\nFaktor pencahayaan alami maupun buatan juga sangat memengaruhi tampilan akhir. Warna keramik lantai teras yang bagus akan terlihat berbeda saat terpapar terik matahari siang dibandingkan saat tertimpa cahaya lampu taman di malam hari. Selalu uji sampel material di lokasi aslinya pada berbagai waktu sebelum melakukan pembelian dalam jumlah besar. Panduan komprehensif mengenai teknik penyelarasan ini tersedia di halaman tips desain dan inspirasi area luar rumah.\r\n\r\nDampak Tampilan Fasad Terhadap Nilai Investasi Properti\r\nDalam industri real estate, daya tarik visual dari luar sering kali menjadi penentu utama minat calon pembeli atau penyewa. Sebuah rumah tapak yang berada di lingkungan perumahan padat di Surabaya atau kawasan berkembang di Sidoarjo akan memiliki nilai tawar yang jauh lebih tinggi jika fasadnya dirancang dengan apik. Calon pembeli secara tidak sadar mengaitkan kondisi eksterior yang terawat dengan kualitas struktur bangunan secara keseluruhan.\r\n\r\nInvestasi pada material pelapis lantai area depan bukanlah pengeluaran yang sia-sia, melainkan strategi peningkatan aset. Properti komersial seperti ruko atau kafe juga sangat bergantung pada tampilan area depan untuk menarik pengunjung. Pemilihan rona dan material yang tepat akan memperkuat identitas bisnis dan menciptakan suasana yang mengundang orang untuk datang.\r\n\r\nPanduan Merawat Permukaan Eksterior Agar Tetap Prima	\N	2026-04-28 07:18:03	2026-04-28 07:18:03	Brighton	https://www.brighton.co.id/about/articles-all/warna-keramik-lantai-teras-yang-bagus-untuk-hunian-ideal
\.


--
-- Data for Name: property_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.property_details (id, listing_id, house_type, land_area, building_area, bedrooms, bathrooms, floors, certificate, facilities, is_kpr, created_at, updated_at) FROM stdin;
7	9	Perumahan	60	60	2	1	1	SHM PBG	Listrik, PDAM, WiFi	f	2026-05-13 00:35:11	2026-05-13 00:35:11
8	10	Gudang Terbengkalai	10305	10305	\N	2	1	HGB	Garasi : 1\r\nCarport : 1\r\nPLN : 1300W	f	2026-05-13 00:40:02	2026-05-13 00:40:02
9	11	Rumah	829	400	10	9	1	SHM	Garasi and Garden	f	2026-05-13 00:47:40	2026-05-13 00:47:40
10	13	Rumah	86	50	2	1	1	SHM - Sertifikat Hak Milik	-	f	2026-05-13 00:58:13	2026-05-13 00:58:13
11	14	Rumah	204	300	4	1	2	SHM	Listrik	f	2026-05-13 03:49:54	2026-05-13 03:49:54
12	15	Gudang/Ruko	873	690	\N	3	1	HGB	Garasi+Kamar Tidur 3	f	2026-05-13 03:58:55	2026-05-13 03:58:55
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: site_visits; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.site_visits (id, user_id, session_id, ip_address, user_agent, method, path, url, referer, visited_at, created_at, updated_at) FROM stdin;
1	\N	FXlXcpdMfJ4pUtwB3Db9CPpOjWVKHx5RDMLpa07e	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 19:34:24	2026-05-12 19:34:24	2026-05-12 19:34:24
2	\N	5wNqDB2tAbug3kwjXuZfmxh0CWjKcIgefgBA6CQA	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 19:34:44	2026-05-12 19:34:44	2026-05-12 19:34:44
3	\N	l4CPwRDCP02CoO8IubKriQydZEI03A4KcEZX27wV	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	login	http://127.0.0.1:8000/login	\N	2026-05-12 19:34:51	2026-05-12 19:34:51	2026-05-12 19:34:51
4	\N	MblQ5AR1rq7jYBagUF6FNYakBxQGtSRWLFwEUho6	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 19:39:50	2026-05-12 19:39:50	2026-05-12 19:39:50
5	\N	DE3HtVVEdjEdKElZQWj5Y4RppVsRdYfVNLlxStkJ	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 19:40:29	2026-05-12 19:40:29	2026-05-12 19:40:29
6	\N	2avQtraJak7C1NU6UmaG9F1QKaMv9aqfWsTA2oZn	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 19:45:14	2026-05-12 19:45:14	2026-05-12 19:45:14
7	\N	50TbW1eMMbux4SkA0pw2nd5Yn8D55L7rmikyE9h5	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 20:22:06	2026-05-12 20:22:08	2026-05-12 20:22:08
8	\N	OdTywPwQOB3MmCyUiK7Mem3myoNl3vUY5xtInpRH	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://localhost:8000	\N	2026-05-12 20:22:22	2026-05-12 20:22:22	2026-05-12 20:22:22
1224	\N	16JqX0HpKgLLLn42NR58HHUm8bCLVkBgsBEkjGK5	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 20:54:51	2026-05-12 20:54:51	2026-05-12 20:54:51
1225	\N	OdTywPwQOB3MmCyUiK7Mem3myoNl3vUY5xtInpRH	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://localhost:8000	\N	2026-05-12 20:56:45	2026-05-12 20:56:45	2026-05-12 20:56:45
1226	\N	ItCBg7eBQ4dysuMZMGgQD6ViVnmjiazSixwq0OAN	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/	2026-05-12 20:56:50	2026-05-12 20:56:50	2026-05-12 20:56:50
1227	\N	OdTywPwQOB3MmCyUiK7Mem3myoNl3vUY5xtInpRH	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://localhost:8000	http://localhost:8000/	2026-05-12 20:58:50	2026-05-12 20:58:50	2026-05-12 20:58:50
1228	\N	oPOyGSU1TbfUagCHWL2SiSx7h7XtR7FXleoIsWb5	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 20:58:56	2026-05-12 20:58:56	2026-05-12 20:58:56
1229	\N	6HkRsHEDJ7zzG1ESSqIRU9KLJvjReTtLR9EsdISS	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 21:22:46	2026-05-12 21:22:47	2026-05-12 21:22:47
1230	\N	xDoC6K1MVfSxubQXrwi9ds0Eh7qCr8MD5JkmWclG	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 21:42:14	2026-05-12 21:42:14	2026-05-12 21:42:14
1231	\N	2RmNv3R4yqYfkDzYjuzbuAe3HXVUTtzHwAqNhTtt	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 21:42:26	2026-05-12 21:42:26	2026-05-12 21:42:26
1232	\N	YroFwQ5bkDmizoFDRqe9XrJTUNO33U7JRT0d0FOE	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 21:44:36	2026-05-12 21:44:36	2026-05-12 21:44:36
1233	\N	2jncKylRHtZwz9YfOcP6aLrRUIHJLSKgYAREgIJD	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/	2026-05-12 21:47:05	2026-05-12 21:47:05	2026-05-12 21:47:05
1234	\N	94xfdM8HBP3QLIJhS1fk7gcZdTvjojcuB8D96Its	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 21:47:30	2026-05-12 21:47:30	2026-05-12 21:47:30
1235	\N	xfSTs8TqkHSWKoWNfh2SQfWwIyqfNtnZxQjweTfl	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 21:49:40	2026-05-12 21:49:40	2026-05-12 21:49:40
1236	10	Z8F0JS7jIz3MFEIffTfliTY1avoN2lRKGPo2KdeR	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	dashboard	http://127.0.0.1:8000/dashboard	http://127.0.0.1:8000/	2026-05-12 21:49:51	2026-05-12 21:49:51	2026-05-12 21:49:51
1237	10	Z8F0JS7jIz3MFEIffTfliTY1avoN2lRKGPo2KdeR	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	dashboard	http://127.0.0.1:8000/dashboard	http://127.0.0.1:8000/	2026-05-12 21:51:11	2026-05-12 21:51:11	2026-05-12 21:51:11
1238	\N	FfPQNEjIztoyHVryothQsTd40ZeNjMF3GMOo0woc	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/dashboard	2026-05-12 21:51:15	2026-05-12 21:51:15	2026-05-12 21:51:15
1239	10	y4fGZo8fFtCdnj2NUTvDyP24TniOXek3YuumhCp1	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/	2026-05-12 21:51:20	2026-05-12 21:51:20	2026-05-12 21:51:20
1240	\N	0ZoY8VzVznyKn6MCCwB3GaC57Qj0CfjJfZErzb59	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/	2026-05-12 21:51:25	2026-05-12 21:51:25	2026-05-12 21:51:25
1241	\N	JGjEMW0hXiDS4a0BoUZSP1kY19ALPZpbsQeUG4eR	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-12 23:54:01	2026-05-12 23:54:01	2026-05-12 23:54:01
1242	\N	JGjEMW0hXiDS4a0BoUZSP1kY19ALPZpbsQeUG4eR	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/	2026-05-12 23:54:15	2026-05-12 23:54:15	2026-05-12 23:54:15
1243	\N	JGjEMW0hXiDS4a0BoUZSP1kY19ALPZpbsQeUG4eR	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/	2026-05-12 23:54:23	2026-05-12 23:54:23	2026-05-12 23:54:23
1244	\N	JGjEMW0hXiDS4a0BoUZSP1kY19ALPZpbsQeUG4eR	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/	2026-05-12 23:55:33	2026-05-12 23:55:33	2026-05-12 23:55:33
1245	\N	JGjEMW0hXiDS4a0BoUZSP1kY19ALPZpbsQeUG4eR	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/	2026-05-12 23:57:00	2026-05-12 23:57:00	2026-05-12 23:57:00
1246	\N	JGjEMW0hXiDS4a0BoUZSP1kY19ALPZpbsQeUG4eR	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/	2026-05-13 00:03:44	2026-05-13 00:03:44	2026-05-13 00:03:44
1247	\N	JGjEMW0hXiDS4a0BoUZSP1kY19ALPZpbsQeUG4eR	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	http://127.0.0.1:8000/	2026-05-13 00:03:54	2026-05-13 00:03:54	2026-05-13 00:03:54
1307	\N	FKvwyMAPxuIq7WbdlgUcg4y8TlGiujOfbafkXT6G	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-13 03:39:44	2026-05-13 03:39:44	2026-05-13 03:39:44
1308	10	kDF9t9Pgxxk1mhPAwFCUSTjIXRVBlTW6nzmD8pli	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	GET	/	http://127.0.0.1:8000	\N	2026-05-13 03:48:27	2026-05-13 03:48:27	2026-05-13 03:48:27
\.


--
-- Data for Name: testimonials; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.testimonials (id, name, job, message, photo, is_active, created_at, updated_at, rating) FROM stdin;
1	Rizal	\N	Hewjalwjaejwalhdlawdada	testimonials/6gIShec0ykyyr7dzkNUMHOD6CHtoO88g5K3x3UOO.png	t	2026-04-28 07:15:30	2026-05-09 15:51:30	5
\.


--
-- Data for Name: upgrade_requests; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.upgrade_requests (id, user_id, nama, email, no_hp, alamat, role, nama_agen, nama_pemilik_agen, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role, status, requested_role, phone, address, profile_photo) FROM stdin;
1	Rizal Efendi	efendi2k18@gmail.com	\N	$2y$12$78VIa/lgPypmXhMqPYBPLOSz9FCM88B2KxjrdEQIOw8TUhH4Ci7se	\N	2026-04-26 10:07:58	2026-05-01 07:56:57	admin	normal	\N	0895347042844	Karangwaru	profile-photos/JRW69oQ26gCJ7OMfWLmicGRGCU5YkAO8nDc7193y.png
2	Test User	test@example.com	2026-04-26 12:09:26	$2y$12$OcX/WZ2FvBevwBWhfsFdGOYHGlikQguQ4C7c2oW2Nmc2FCrXc0XK.	aNXs7gesMv	2026-04-26 12:09:26	2026-04-26 12:09:26	user	pending	\N	\N	\N	\N
4	Nicetry agency	agencynicetry@gmail.com	\N	$2y$12$KDCCyR4HKdrHS52oDENhyO2hcPGXoT7Nspkwerb1I5vLaDQBlJwsa	\N	2026-04-28 19:21:18	2026-04-29 18:27:45	agen	approved	\N	\N	\N	\N
5	Rizal Efendy	markingacident@gmail.com	\N	$2y$12$XopIOmXttsddMvkQPcNCjuhqiOipEPyjIuj.KTrE9Ne9qBIxMXM9G	\N	2026-04-28 19:25:32	2026-04-28 19:25:32	user	pending	\N	\N	\N	\N
6	Arsantara	arsantara.management@gmail.com	\N	$2y$12$RI6oNMKF76fotGC8OFFZce2Ppb.8iJ5b02OPeLgVuqTBYtkCChbpK	\N	2026-04-29 18:40:44	2026-04-29 18:41:05	pemilik	approved	\N	\N	\N	\N
7	Emporium Club	emporiumclub44@gmail.com	\N	$2y$12$Ik1zf9GFWpG6zLQRY8XJ1.e34PTVfTL5FTJUtIPJ87LHw9zRvblCW	\N	2026-04-29 18:42:12	2026-04-29 18:42:47	agen	approved	\N	\N	\N	\N
9	Rizal Efendi	rotherphantom@gmail.com	\N	$2y$12$ceBK8/AqgzYcDvTrhLYoYeoic3Bl/k7Lq5aNnoC7N1eNOwG2xoXkG	\N	2026-05-08 12:02:04	2026-05-08 12:06:36	agen	approved	\N	\N	\N	\N
8	Rebel Cloth	rebelcloth46@gmail.com	\N	$2y$12$vaHdu.I2NHjHTVqZgmBBaevvQ3L0vD6MBUZ0k67TcbEbmY2dK1Bfm	\N	2026-05-04 07:34:31	2026-05-09 15:33:39	agen	approved	\N	\N	\N	\N
10	ilham	coldmysterious30@gmail.com	\N	$2y$12$p55MUxKdGqx1JbqTFpxe8OlQCgXW6rIQrrxG5Qd2RhNCeulPbqriS	xZeDcDZoUZ6bLs12Is86uR5Mhl8Z4w0YeKffjn9YSMbRAuqWeCK01dO5vvXA	2026-05-12 21:49:51	2026-05-13 00:03:09	admin	normal	\N	\N	\N	\N
\.


--
-- Name: agent_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.agent_requests_id_seq', 1, false);


--
-- Name: car_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.car_details_id_seq', 2, true);


--
-- Name: carousels_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carousels_id_seq', 10, true);


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categories_id_seq', 18, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: favorites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.favorites_id_seq', 1, false);


--
-- Name: job_applications_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.job_applications_id_seq', 1, true);


--
-- Name: job_vacancies_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.job_vacancies_id_seq', 4, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: listing_images_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.listing_images_id_seq', 62, true);


--
-- Name: listing_views_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.listing_views_id_seq', 73, true);


--
-- Name: listings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.listings_id_seq', 15, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 42, true);


--
-- Name: motorcycle_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.motorcycle_details_id_seq', 1, true);


--
-- Name: organization_members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.organization_members_id_seq', 3, true);


--
-- Name: partners_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.partners_id_seq', 2, true);


--
-- Name: post_images_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.post_images_id_seq', 1, true);


--
-- Name: posts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.posts_id_seq', 1, true);


--
-- Name: property_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.property_details_id_seq', 12, true);


--
-- Name: site_visits_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.site_visits_id_seq', 1308, true);


--
-- Name: testimonials_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.testimonials_id_seq', 1, true);


--
-- Name: upgrade_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.upgrade_requests_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 10, true);


--
-- Name: agent_requests agent_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agent_requests
    ADD CONSTRAINT agent_requests_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: car_details car_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.car_details
    ADD CONSTRAINT car_details_pkey PRIMARY KEY (id);


--
-- Name: carousels carousels_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carousels
    ADD CONSTRAINT carousels_pkey PRIMARY KEY (id);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: categories categories_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_slug_unique UNIQUE (slug);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: favorites favorites_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favorites
    ADD CONSTRAINT favorites_pkey PRIMARY KEY (id);


--
-- Name: job_applications job_applications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_applications
    ADD CONSTRAINT job_applications_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: job_vacancies job_vacancies_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_vacancies
    ADD CONSTRAINT job_vacancies_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: listing_images listing_images_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listing_images
    ADD CONSTRAINT listing_images_pkey PRIMARY KEY (id);


--
-- Name: listing_views listing_views_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listing_views
    ADD CONSTRAINT listing_views_pkey PRIMARY KEY (id);


--
-- Name: listings listings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listings
    ADD CONSTRAINT listings_pkey PRIMARY KEY (id);


--
-- Name: listings listings_product_code_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listings
    ADD CONSTRAINT listings_product_code_unique UNIQUE (product_code);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: motorcycle_details motorcycle_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.motorcycle_details
    ADD CONSTRAINT motorcycle_details_pkey PRIMARY KEY (id);


--
-- Name: organization_members organization_members_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organization_members
    ADD CONSTRAINT organization_members_pkey PRIMARY KEY (id);


--
-- Name: partners partners_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.partners
    ADD CONSTRAINT partners_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: post_images post_images_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.post_images
    ADD CONSTRAINT post_images_pkey PRIMARY KEY (id);


--
-- Name: posts posts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts
    ADD CONSTRAINT posts_pkey PRIMARY KEY (id);


--
-- Name: property_details property_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property_details
    ADD CONSTRAINT property_details_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: site_visits site_visits_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.site_visits
    ADD CONSTRAINT site_visits_pkey PRIMARY KEY (id);


--
-- Name: testimonials testimonials_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.testimonials
    ADD CONSTRAINT testimonials_pkey PRIMARY KEY (id);


--
-- Name: upgrade_requests upgrade_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.upgrade_requests
    ADD CONSTRAINT upgrade_requests_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: listing_views_session_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX listing_views_session_id_index ON public.listing_views USING btree (session_id);


--
-- Name: listing_views_viewed_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX listing_views_viewed_at_index ON public.listing_views USING btree (viewed_at);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: site_visits_session_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX site_visits_session_id_index ON public.site_visits USING btree (session_id);


--
-- Name: site_visits_visited_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX site_visits_visited_at_index ON public.site_visits USING btree (visited_at);


--
-- Name: agent_requests agent_requests_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agent_requests
    ADD CONSTRAINT agent_requests_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: car_details car_details_listing_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.car_details
    ADD CONSTRAINT car_details_listing_id_foreign FOREIGN KEY (listing_id) REFERENCES public.listings(id) ON DELETE CASCADE;


--
-- Name: favorites favorites_listing_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favorites
    ADD CONSTRAINT favorites_listing_id_foreign FOREIGN KEY (listing_id) REFERENCES public.listings(id) ON DELETE CASCADE;


--
-- Name: favorites favorites_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favorites
    ADD CONSTRAINT favorites_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: job_applications job_applications_job_vacancy_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_applications
    ADD CONSTRAINT job_applications_job_vacancy_id_foreign FOREIGN KEY (job_vacancy_id) REFERENCES public.job_vacancies(id) ON DELETE CASCADE;


--
-- Name: listing_images listing_images_listing_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listing_images
    ADD CONSTRAINT listing_images_listing_id_foreign FOREIGN KEY (listing_id) REFERENCES public.listings(id) ON DELETE CASCADE;


--
-- Name: listing_views listing_views_listing_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listing_views
    ADD CONSTRAINT listing_views_listing_id_foreign FOREIGN KEY (listing_id) REFERENCES public.listings(id) ON DELETE CASCADE;


--
-- Name: listing_views listing_views_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listing_views
    ADD CONSTRAINT listing_views_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: listings listings_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listings
    ADD CONSTRAINT listings_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: listings listings_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.listings
    ADD CONSTRAINT listings_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: motorcycle_details motorcycle_details_listing_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.motorcycle_details
    ADD CONSTRAINT motorcycle_details_listing_id_foreign FOREIGN KEY (listing_id) REFERENCES public.listings(id) ON DELETE CASCADE;


--
-- Name: post_images post_images_post_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.post_images
    ADD CONSTRAINT post_images_post_id_foreign FOREIGN KEY (post_id) REFERENCES public.posts(id) ON DELETE CASCADE;


--
-- Name: property_details property_details_listing_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property_details
    ADD CONSTRAINT property_details_listing_id_foreign FOREIGN KEY (listing_id) REFERENCES public.listings(id) ON DELETE CASCADE;


--
-- Name: site_visits site_visits_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.site_visits
    ADD CONSTRAINT site_visits_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: upgrade_requests upgrade_requests_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.upgrade_requests
    ADD CONSTRAINT upgrade_requests_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict zN6HTeISJu2fhRAKHcFrbVlpMPP0PIEvDAENxr9RD9UG8L2Wa1q11eOFfj82Inw

