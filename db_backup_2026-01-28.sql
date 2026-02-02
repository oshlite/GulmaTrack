--
-- PostgreSQL database dump
--

-- Dumped from database version 14.5
-- Dumped by pg_dump version 14.5

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
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
-- Name: data_gulma; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.data_gulma (
    id bigint NOT NULL,
    wilayah_id bigint,
    id_feature character varying(255),
    status_gulma character varying(255),
    persentase integer,
    pg character varying(255),
    fm character varying(255),
    seksi character varying(255),
    neto numeric(10,2),
    hasil numeric(10,2),
    umur numeric(10,2),
    tnm_sts character varying(255),
    activitas character varying(255),
    kategori character varying(255),
    tanggal text,
    tk_ha numeric(10,2),
    total_tk numeric(10,2),
    import_log_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.data_gulma OWNER TO postgres;

--
-- Name: data_gulma_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.data_gulma_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.data_gulma_id_seq OWNER TO postgres;

--
-- Name: data_gulma_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.data_gulma_id_seq OWNED BY public.data_gulma.id;


--
-- Name: drones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.drones (
    id bigint NOT NULL,
    judul character varying(255) NOT NULL,
    lokasi character varying(255) NOT NULL,
    tanggal_perencanaan date NOT NULL,
    pdf_path character varying(255) NOT NULL,
    pdf_filename character varying(255) NOT NULL,
    user_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    persen_gulma numeric(5,2)
);


ALTER TABLE public.drones OWNER TO postgres;

--
-- Name: COLUMN drones.persen_gulma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.drones.persen_gulma IS 'Persentase gulma';


--
-- Name: drones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.drones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.drones_id_seq OWNER TO postgres;

--
-- Name: drones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.drones_id_seq OWNED BY public.drones.id;


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


ALTER TABLE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: gulma_photos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.gulma_photos (
    id bigint NOT NULL,
    kategori character varying(255) NOT NULL,
    foto_path character varying(255) NOT NULL,
    deskripsi text,
    uploaded_by bigint NOT NULL,
    file_size bigint,
    mime_type character varying(100),
    is_primary boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT gulma_photos_kategori_check CHECK (((kategori)::text = ANY ((ARRAY['bersih'::character varying, 'ringan'::character varying, 'sedang'::character varying, 'berat'::character varying])::text[])))
);


ALTER TABLE public.gulma_photos OWNER TO postgres;

--
-- Name: gulma_photos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.gulma_photos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.gulma_photos_id_seq OWNER TO postgres;

--
-- Name: gulma_photos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.gulma_photos_id_seq OWNED BY public.gulma_photos.id;


--
-- Name: import_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.import_logs (
    id bigint NOT NULL,
    nama_file character varying(255) NOT NULL,
    wilayah_id character varying(100) NOT NULL,
    tahun integer,
    bulan integer,
    minggu integer,
    jumlah_records integer DEFAULT 0 NOT NULL,
    jumlah_berhasil integer DEFAULT 0 NOT NULL,
    jumlah_gagal integer DEFAULT 0 NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    error_log text,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT import_logs_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'success'::character varying, 'partial'::character varying, 'failed'::character varying])::text[])))
);


ALTER TABLE public.import_logs OWNER TO postgres;

--
-- Name: import_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.import_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.import_logs_id_seq OWNER TO postgres;

--
-- Name: import_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.import_logs_id_seq OWNED BY public.import_logs.id;


--
-- Name: map_publications; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.map_publications (
    id bigint NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    published_at timestamp(0) without time zone,
    published_by bigint,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    import_log_id bigint,
    tahun integer,
    bulan integer,
    minggu integer
);


ALTER TABLE public.map_publications OWNER TO postgres;

--
-- Name: map_publications_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.map_publications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.map_publications_id_seq OWNER TO postgres;

--
-- Name: map_publications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.map_publications_id_seq OWNED BY public.map_publications.id;


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


ALTER TABLE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


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
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO postgres;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.personal_access_tokens_id_seq OWNER TO postgres;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    role character varying(255) DEFAULT 'guest'::character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['guest'::character varying, 'admin'::character varying])::text[])))
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


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: wilayah; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.wilayah (
    id bigint NOT NULL,
    wilayah_id integer NOT NULL,
    nama_wilayah character varying(255) NOT NULL,
    deskripsi text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wilayah OWNER TO postgres;

--
-- Name: wilayah_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.wilayah_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wilayah_id_seq OWNER TO postgres;

--
-- Name: wilayah_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.wilayah_id_seq OWNED BY public.wilayah.id;


--
-- Name: data_gulma id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_gulma ALTER COLUMN id SET DEFAULT nextval('public.data_gulma_id_seq'::regclass);


--
-- Name: drones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drones ALTER COLUMN id SET DEFAULT nextval('public.drones_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: gulma_photos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gulma_photos ALTER COLUMN id SET DEFAULT nextval('public.gulma_photos_id_seq'::regclass);


--
-- Name: import_logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.import_logs ALTER COLUMN id SET DEFAULT nextval('public.import_logs_id_seq'::regclass);


--
-- Name: map_publications id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.map_publications ALTER COLUMN id SET DEFAULT nextval('public.map_publications_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: wilayah id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wilayah ALTER COLUMN id SET DEFAULT nextval('public.wilayah_id_seq'::regclass);


--
-- Data for Name: data_gulma; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.data_gulma (id, wilayah_id, id_feature, status_gulma, persentase, pg, fm, seksi, neto, hasil, umur, tnm_sts, activitas, kategori, tanggal, tk_ha, total_tk, import_log_id, created_at, updated_at) FROM stdin;
129	17	518B	\N	\N	3	05	518B	11.76	11.76	0.90	SC	Weeding man.	Ringan	19-Jan	4.00	47.04	16	2026-01-24 10:08:36	2026-01-27 14:54:12
290	16	507H3	\N	\N	3	5	507H3	4.42	4.42	12.80	\N	Weeding man.	\N		4.00	17.68	12	2026-01-26 08:10:28	2026-01-26 08:10:28
1	16	506C1	\N	\N	3	05	506C1	7.36	7.36	9.70	FC	Weeding man.	Ringan	2-Nov	4.00	29.44	18	2026-01-24 10:07:51	2026-01-28 13:37:47
2	16	506C2	\N	\N	3	05	506C2	10.88	10.88	9.30	FC	Weeding man.	Ringan	3-Nov	4.00	43.52	18	2026-01-24 10:07:51	2026-01-28 13:37:47
3	16	507F	\N	\N	3	05	507F	13.54	13.54	3.80	SC	Weeding man.	Ringan	4-Nov	4.00	54.16	18	2026-01-24 10:07:51	2026-01-28 13:37:47
5	16	507H6	\N	\N	3	05	507H6	4.58	4.58	11.30	FC	Weeding man.	Ringan	8-Nov	4.00	18.32	18	2026-01-24 10:07:51	2026-01-28 13:37:47
7	16	509F	\N	\N	3	05	509F	11.52	11.52	3.40	FC	Weeding man.	Ringan	8-Nov	4.00	46.08	18	2026-01-24 10:07:51	2026-01-28 13:37:47
9	16	510C1B	\N	\N	3	05	510C1B	7.32	7.32	4.40	SC	Weeding man.	Ringan	8-Nov	4.00	29.28	18	2026-01-24 10:07:51	2026-01-28 13:37:47
10	16	502A1	\N	\N	3	05	502A1	6.12	6.12	3.50	FC	Weeding man.	Ringan	8-Nov	4.00	24.48	18	2026-01-24 10:07:51	2026-01-28 13:37:47
11	16	502A2	\N	\N	3	05	502A2	9.69	9.69	3.50	FC	Weeding man.	Ringan	8-Nov	4.00	38.76	18	2026-01-24 10:07:51	2026-01-28 13:37:47
12	16	501C1	\N	\N	3	05	501C1	11.51	11.51	5.70	FC	Weeding man.	Ringan	8-Nov	4.00	46.04	18	2026-01-24 10:07:51	2026-01-28 13:37:47
13	16	501C2	\N	\N	3	05	501C2	8.92	8.92	5.20	FC	Weeding man.	Ringan	8-Nov	4.00	35.68	18	2026-01-24 10:07:51	2026-01-28 13:37:47
14	16	504B2B	\N	\N	3	05	504B2B	3.29	3.29	8.40	FC	Weeding man.	Ringan	8-Nov	4.00	13.16	18	2026-01-24 10:07:51	2026-01-28 13:37:47
15	16	504A1	\N	\N	3	05	504A1	3.73	3.73	5.50	FC	Weeding man.	Ringan	8-Nov	4.00	14.92	18	2026-01-24 10:07:51	2026-01-28 13:37:47
16	16	504A3	\N	\N	3	05	504A3	3.45	3.45	5.30	FC	Weeding man.	Ringan	8-Nov	4.00	13.80	18	2026-01-24 10:07:51	2026-01-28 13:37:47
17	16	504A4	\N	\N	3	05	504A4	5.75	5.75	4.90	FC	Weeding man.	Ringan	8-Nov	4.00	23.00	18	2026-01-24 10:07:51	2026-01-28 13:37:47
18	17	512A	\N	\N	3	05	512A	12.74	12.74	15.50	FC	Weeding man.	Ringan	7-Nov	4.00	50.96	18	2026-01-24 10:07:51	2026-01-28 13:37:47
19	17	512J	\N	\N	3	05	512J	11.83	11.27	6.40	FC	Weeding man.	Ringan	2-Nov	3.00	33.81	18	2026-01-24 10:07:51	2026-01-28 13:37:47
20	17	520B	\N	\N	3	05	520B	9.30	9.30	13.40	FC	Weeding man.	Ringan	3-Nov	3.00	27.90	18	2026-01-24 10:07:51	2026-01-28 13:37:47
21	17	514B1	\N	\N	3	05	514B1	8.15	8.15	12.60	FC	Weeding man.	Ringan	3-Nov	3.00	24.45	18	2026-01-24 10:07:51	2026-01-28 13:37:47
22	17	514B2	\N	\N	3	05	514B2	4.40	4.40	12.60	FC	Weeding man.	Ringan	3-Nov	3.00	13.20	18	2026-01-24 10:07:51	2026-01-28 13:37:47
23	17	514C	\N	\N	3	05	514C	13.36	12.49	3.00	FC	Weeding man.	Ringan	7-Nov	3.00	37.47	18	2026-01-24 10:07:51	2026-01-28 13:37:47
24	17	518E	\N	\N	3	05	518E	14.09	14.09	15.70	FC	Weeding man.	Ringan	3-Nov	3.00	42.27	18	2026-01-24 10:07:51	2026-01-28 13:37:47
25	17	518F	\N	\N	3	05	518F	12.18	12.18	14.90	FC	Weeding man.	Ringan	7-Nov	3.00	36.54	18	2026-01-24 10:07:51	2026-01-28 13:37:47
26	17	525A	\N	\N	3	05	525A	15.38	15.38	12.90	FC	Weeding man.	Ringan	3-Nov	3.00	46.14	18	2026-01-24 10:07:51	2026-01-28 13:37:47
27	17	525F2	\N	\N	3	05	525F2	8.61	8.56	3.00	FC	Weeding man.	Ringan	3-Nov	3.00	25.68	18	2026-01-24 10:07:51	2026-01-28 13:37:47
28	18	530A	\N	\N	3	05	530A	11.34	11.34	10.30	FC	Weeding man.	Ringan	7-Nov	4.00	45.36	18	2026-01-24 10:07:51	2026-01-28 13:37:47
29	18	522C1	\N	\N	3	05	522C1	11.27	11.27	14.40	FC	Weeding man.	Ringan	2-Nov	4.00	45.08	18	2026-01-24 10:07:51	2026-01-28 13:37:47
30	18	531E	\N	\N	3	05	531E	12.79	12.79	5.90	FC	Weeding man.	Ringan	6-Nov	4.00	51.16	18	2026-01-24 10:07:51	2026-01-28 13:37:47
31	18	530E3	\N	\N	3	05	530E3	9.95	9.95	14.90	FC	Weeding man.	Ringan	7-Nov	4.00	39.80	18	2026-01-24 10:07:51	2026-01-28 13:37:47
32	18	522C2	\N	\N	3	05	522C2	8.09	8.09	14.50	FC	Weeding man.	Ringan	2-Nov	4.00	32.36	18	2026-01-24 10:07:51	2026-01-28 13:37:47
33	18	537B2	\N	\N	3	05	537B2	10.51	10.51	8.60	FC	Weeding man.	Ringan	6-Nov	4.00	42.04	18	2026-01-24 10:07:51	2026-01-28 13:37:47
34	18	537A	\N	\N	3	05	537A	8.47	8.47	13.10	FC	Weeding man.	Ringan	7-Nov	4.00	33.88	18	2026-01-24 10:07:51	2026-01-28 13:37:47
35	18	536C2a	\N	\N	3	05	536C2a	4.93	4.93	8.50	FC	Weeding man.	Ringan	2-Nov	4.00	19.72	18	2026-01-24 10:07:51	2026-01-28 13:37:47
36	18	536C2b	\N	\N	3	05	536C2b	3.34	3.34	13.10	FC	Weeding man.	Ringan	6-Nov	4.00	13.36	18	2026-01-24 10:07:51	2026-01-28 13:37:47
37	18	536C2c	\N	\N	3	05	536C2c	2.38	2.38	13.10	FC	Weeding man.	Ringan	7-Nov	4.00	9.52	18	2026-01-24 10:07:51	2026-01-28 13:37:47
38	18	523B	\N	\N	3	05	523B	4.20	4.20	4.40	FC	Weeding man.	Ringan	6-Nov	4.00	16.80	18	2026-01-24 10:07:51	2026-01-28 13:37:47
39	18	523C	\N	\N	3	05	523C	5.65	5.65	2.60	SC	Weeding man.	Ringan	7-Nov	4.00	22.60	18	2026-01-24 10:07:51	2026-01-28 13:37:47
41	18	528D	\N	\N	3	05	528D	9.32	9.32	12.10	SC	Weeding man.	Ringan	6-Nov	4.00	37.28	18	2026-01-24 10:07:51	2026-01-28 13:37:47
42	19	546I4	\N	\N	3	05	546I4	10.94	5.63	6.10	FC	Weeding man.	Sedang	2-Nov	5.00	54.70	18	2026-01-24 10:07:51	2026-01-28 13:37:47
43	19	541I1	\N	\N	3	05	541I1	12.89	7.82	4.30	FC	Weeding man.	Ringan	7-Nov	4.00	51.08	18	2026-01-24 10:07:51	2026-01-28 13:37:47
44	19	541D	\N	\N	3	05	541D	9.18	5.63	5.40	FC	Weeding man.	Ringan	5-Nov	4.00	36.72	18	2026-01-24 10:07:51	2026-01-28 13:37:47
46	19	545A2B	\N	\N	3	05	545A2B	6.82	5.63	12.30	FC	Weeding man.	Ringan	4-Nov	4.00	27.28	18	2026-01-24 10:07:51	2026-01-28 13:37:47
47	19	534A1	\N	\N	3	05	534A1	10.03	12.77	5.00	FC	Weeding man.	Ringan	6-Nov	4.00	37.88	18	2026-01-24 10:07:51	2026-01-28 13:37:47
48	19	534D	\N	\N	3	05	534D	12.01	11.33	10.40	SC	Weeding man.	Ringan	5-Nov	4.00	48.04	18	2026-01-24 10:07:51	2026-01-28 13:37:47
49	19	534A2	\N	\N	3	05	534A2	9.39	11.33	5.20	FC	Weeding man.	Ringan	6-Nov	4.00	36.68	18	2026-01-24 10:07:51	2026-01-28 13:37:47
50	20	585A3	\N	\N	3	06	585A3	4.27	4.27	10.90	FC	Weeding man.	Ringan	30-Oct	3.00	12.81	18	2026-01-24 10:07:51	2026-01-28 13:37:47
51	20	589D7	\N	\N	3	06	589D7	6.65	6.65	3.20	FC	Weeding man.	Ringan	26-Oct	3.00	19.95	18	2026-01-24 10:07:51	2026-01-28 13:37:47
291	16	505F	\N	\N	3	5	505F	7.31	7.31	12.50	\N	Weeding man.	\N		4.00	29.24	12	2026-01-26 08:10:28	2026-01-26 08:10:28
6	16	507H4	\N	\N	3	05	507H4	4.38	4.38	10.80	FC	Weeding man.	Ringan	8-Nov	4.00	17.52	18	2026-01-24 10:07:51	2026-01-28 13:37:47
8	16	510C1A	\N	\N	3	05	510C1A	6.30	6.30	4.50	SC	Weeding man.	Ringan	8-Nov	4.00	25.20	18	2026-01-24 10:07:51	2026-01-28 13:37:47
52	20	584C1	\N	\N	3	06	584C1	6.77	6.77	11.30	FC	Weeding man.	Ringan	29-Oct	3.00	20.31	18	2026-01-24 10:07:51	2026-01-28 13:37:47
53	20	584C2	\N	\N	3	06	584C2	8.50	8.50	11.40	FC	Weeding man.	Ringan	30-Oct	3.00	25.50	18	2026-01-24 10:07:51	2026-01-28 13:37:47
55	20	584A2	\N	\N	3	06	584A2	9.00	9.00	5.50	SC	Weeding man.	Ringan	30-Oct	3.00	27.00	18	2026-01-24 10:07:51	2026-01-28 13:37:47
57	20	582A2	\N	\N	3	06	582A2	10.32	10.32	1.70	FC	Weeding man.	Ringan	30-Oct	3.00	30.96	18	2026-01-24 10:07:51	2026-01-28 13:37:47
59	20	538B	\N	\N	3	06	538B	14.27	14.27	13.50	FC	Weeding man.	Ringan	30-Oct	4.00	57.08	18	2026-01-24 10:07:51	2026-01-28 13:37:47
60	20	538C1	\N	\N	3	06	538C1	5.01	5.01	13.40	FC	Weeding man.	Ringan	29-Oct	4.00	20.04	18	2026-01-24 10:07:51	2026-01-28 13:37:47
61	20	586C2	\N	\N	3	06	586C2	6.97	6.97	3.00	SC	Weeding man.	Ringan	30-Oct	4.00	27.88	18	2026-01-24 10:07:51	2026-01-28 13:37:47
62	20	580A	\N	\N	3	06	580A	8.71	8.71	10.60	SC	Weeding man.	Ringan	29-Oct	4.00	34.84	18	2026-01-24 10:07:51	2026-01-28 13:37:47
63	20	579B1	\N	\N	3	06	579B1	9.85	9.85	2.80	FC	Weeding man.	Ringan	30-Oct	3.00	29.55	18	2026-01-24 10:07:51	2026-01-28 13:37:47
64	20	539C	\N	\N	3	06	539C	9.37	9.37	11.90	FC	Weeding man.	Ringan	29-Oct	3.00	28.11	18	2026-01-24 10:07:51	2026-01-28 13:37:47
65	20	547C	\N	\N	3	06	547C	13.97	13.97	15.60	FC	Weeding man.	Ringan	30-Oct	3.00	41.91	18	2026-01-24 10:07:51	2026-01-28 13:37:48
66	20	538G	\N	\N	3	06	538G	11.23	11.23	1.70	FC	Weeding man.	Ringan	29-Oct	3.00	33.69	18	2026-01-24 10:07:51	2026-01-28 13:37:48
67	20	548H2	\N	\N	3	06	548H2	13.49	13.49	6.30	FC	Weeding man.	Ringan	30-Oct	3.00	40.47	18	2026-01-24 10:07:51	2026-01-28 13:37:48
68	21	551F2A	\N	\N	3	06	551F2A	4.18	4.18	4.80	FC	Weeding man.	Ringan	5-Nov	3.00	12.54	18	2026-01-24 10:07:51	2026-01-28 13:37:48
69	21	551F2B	\N	\N	3	06	551F2B	5.67	5.67	4.90	FC	Weeding man.	Ringan	5-Nov	3.00	17.01	18	2026-01-24 10:07:51	2026-01-28 13:37:48
70	21	557B	\N	\N	3	06	557B	5.11	5.18	2.00	SC	Weeding man.	Ringan	5-Nov	3.00	15.54	18	2026-01-24 10:07:51	2026-01-28 13:37:48
71	21	557B1	\N	\N	3	06	557B1	5.11	5.13	2.00	SC	Weeding man.	Ringan	6-Nov	3.00	15.39	18	2026-01-24 10:07:51	2026-01-28 13:37:48
72	21	557B2	\N	\N	3	06	557B2	10.29	10.36	2.00	SC	Weeding man.	Ringan	6-Nov	3.00	31.08	18	2026-01-24 10:07:51	2026-01-28 13:37:48
73	21	550A1	\N	\N	3	06	550A1	7.59	7.98	3.00	SC	Weeding man.	Ringan	5-Nov	3.00	23.94	18	2026-01-24 10:07:51	2026-01-28 13:37:48
74	21	550A2	\N	\N	3	06	550A2	8.12	8.27	2.80	SC	Weeding man.	Ringan	5-Nov	3.00	24.81	18	2026-01-24 10:07:51	2026-01-28 13:37:48
75	21	556B1	\N	\N	3	06	556B1	13.33	13.92	2.30	SC	Weeding man.	Ringan	5-Nov	3.00	41.76	18	2026-01-24 10:07:51	2026-01-28 13:37:48
76	21	556B2	\N	\N	3	06	556B2	6.92	7.06	17.50	FC	Weeding man.	Ringan	5-Nov	3.00	21.18	18	2026-01-24 10:07:51	2026-01-28 13:37:48
77	21	549B1A	\N	\N	3	06	549B1A	5.74	5.97	1.10	FC	Weeding man.	Ringan	6-Nov	4.00	23.88	18	2026-01-24 10:07:51	2026-01-28 13:37:48
79	21	549B3	\N	\N	3	06	549B3	5.31	5.12	1.40	FC	Weeding man.	Ringan	5-Nov	4.00	20.48	18	2026-01-24 10:07:51	2026-01-28 13:37:48
80	21	587E	\N	\N	3	06	587E	13.08	11.93	4.00	FC	Weeding man.	Ringan	5-Nov	4.00	47.72	18	2026-01-24 10:07:51	2026-01-28 13:37:48
81	21	548D	\N	\N	3	06	548D	4.49	4.20	0.60	FC	Weeding man.	Ringan	6-Nov	4.00	16.80	18	2026-01-24 10:07:51	2026-01-28 13:37:48
83	21	554E2B	\N	\N	3	06	554E2B	6.68	5.82	0.60	FC	Weeding man.	Ringan	4-Nov	3.00	17.46	18	2026-01-24 10:07:51	2026-01-28 13:37:48
84	21	554E1A	\N	\N	3	06	554E1A	4.80	4.80	3.30	FC	Weeding man.	Ringan	5-Nov	3.00	14.40	18	2026-01-24 10:07:51	2026-01-28 13:37:48
85	21	554E1B	\N	\N	3	06	554E1B	5.87	5.87	3.20	FC	Weeding man.	Ringan	5-Nov	3.00	17.61	18	2026-01-24 10:07:51	2026-01-28 13:37:48
86	21	551C1	\N	\N	3	06	551C1	6.51	6.57	2.80	FC	Weeding man.	Ringan	5-Nov	3.00	19.71	18	2026-01-24 10:07:51	2026-01-28 13:37:48
87	21	551C2	\N	\N	3	06	551C2	5.68	5.60	2.60	FC	Weeding man.	Ringan	5-Nov	4.00	22.40	18	2026-01-24 10:07:51	2026-01-28 13:37:48
88	21	551A	\N	\N	3	06	551A	6.56	5.55	3.00	FC	Weeding man.	Ringan	5-Nov	4.00	22.20	18	2026-01-24 10:07:51	2026-01-28 13:37:48
89	21	562E	\N	\N	3	06	562E	10.26	9.47	1.00	FC	Weeding man.	Ringan	6-Nov	4.00	37.88	18	2026-01-24 10:07:51	2026-01-28 13:37:48
90	22	570B2	\N	\N	3	06	570B2	5.83	5.83	9.40	SC	Weeding man.	Ringan	5-Nov	3.00	17.49	18	2026-01-24 10:07:51	2026-01-28 13:37:48
91	22	567D1	\N	\N	3	06	567D1	11.31	11.31	17.60	FC	Weeding man.	Ringan	2-Nov	3.00	33.93	18	2026-01-24 10:07:51	2026-01-28 13:37:48
92	22	567H	\N	\N	3	06	567H	8.44	8.44	14.20	FC	Weeding man.	Ringan	2-Nov	3.00	25.32	18	2026-01-24 10:07:51	2026-01-28 13:37:48
93	22	568A2	\N	\N	3	06	568A2	12.26	12.26	2.40	SC	Weeding man.	Ringan	6-Nov	3.00	36.78	18	2026-01-24 10:07:51	2026-01-28 13:37:48
94	22	568B3	\N	\N	3	06	568B3	9.83	9.83	16.70	FC	Weeding man.	Ringan	5-Nov	3.00	29.49	18	2026-01-24 10:07:51	2026-01-28 13:37:48
95	22	559C1	\N	\N	3	06	559C1	8.60	8.60	8.90	SC	Weeding man.	Ringan	2-Nov	3.00	25.80	18	2026-01-24 10:07:51	2026-01-28 13:37:48
97	22	558E	\N	\N	3	06	558E	10.84	10.84	14.00	FC	Weeding man.	Ringan	2-Nov	3.00	32.52	18	2026-01-24 10:07:51	2026-01-28 13:37:48
98	23	555D1	\N	\N	3	06	555D1	7.70	7.70	2.30	FC	Weeding man.	Bersih	7-Nov	2.00	15.40	18	2026-01-24 10:07:51	2026-01-28 13:37:48
99	23	555D2A	\N	\N	3	06	555D2A	4.43	4.43	2.50	FC	Weeding man.	Bersih	2-Nov	2.00	8.86	18	2026-01-24 10:07:51	2026-01-28 13:37:48
100	23	555D2B	\N	\N	3	06	555D2B	6.12	6.12	2.10	FC	Weeding man.	Bersih	2-Nov	2.00	12.24	18	2026-01-24 10:07:51	2026-01-28 13:37:48
101	23	555D2C	\N	\N	3	06	555D2C	5.70	5.70	1.90	FC	Weeding man.	Bersih	7-Nov	2.00	11.40	18	2026-01-24 10:07:51	2026-01-28 13:37:48
102	23	554F4	\N	\N	3	06	554F4	5.69	5.69	7.40	SC	Weeding man.	Ringan	7-Nov	3.00	17.07	18	2026-01-24 10:07:51	2026-01-28 13:37:48
109	16	507I	\N	\N	3	05	507I	8.57	8.57	4.70	SC	Weeding man.	Ringan	24-Jan	4.00	34.28	16	2026-01-24 10:08:36	2026-01-27 14:54:11
110	16	507D1	\N	\N	3	05	507D1	6.59	6.59	10.50	FC	Weeding man.	Ringan	24-Jan	4.00	26.36	16	2026-01-24 10:08:36	2026-01-27 14:54:11
111	16	507D2	\N	\N	3	05	507D2	7.72	7.72	10.50	FC	Weeding man.	Ringan	24-Jan	4.00	30.88	16	2026-01-24 10:08:36	2026-01-27 14:54:11
112	16	505C	\N	\N	3	05	505C	3.95	3.95	2.80	SC	Weeding man.	Ringan	24-Jan	4.00	15.80	16	2026-01-24 10:08:36	2026-01-27 14:54:11
113	16	506A5	\N	\N	3	05	506A5	3.94	3.94	3.50	FC	Weeding man.	Ringan	24-Jan	4.00	15.76	16	2026-01-24 10:08:36	2026-01-27 14:54:12
114	16	506A2	\N	\N	3	05	506A2	9.27	9.27	3.50	FC	Weeding man.	Ringan	24-Jan	4.00	37.08	16	2026-01-24 10:08:36	2026-01-27 14:54:12
115	16	510A2	\N	\N	3	05	510A2	9.49	9.49	15.30	FC	Weeding man.	Ringan	24-Jan	4.00	37.96	16	2026-01-24 10:08:36	2026-01-27 14:54:12
116	16	510A3	\N	\N	3	05	510A3	4.10	4.10	15.30	FC	Weeding man.	Ringan	24-Jan	4.00	16.40	16	2026-01-24 10:08:36	2026-01-27 14:54:12
117	16	509A2	\N	\N	3	05	509A2	6.76	6.76	10.30	FC	Weeding man.	Ringan	24-Jan	4.00	27.04	16	2026-01-24 10:08:36	2026-01-27 14:54:12
118	16	509B1	\N	\N	3	05	509B1	3.74	3.74	15.20	FC	Weeding man.	Ringan	24-Jan	4.00	14.96	16	2026-01-24 10:08:36	2026-01-27 14:54:12
119	16	503D	\N	\N	3	05	503D	10.85	10.85	14.20	FC	Weeding man.	Ringan	24-Jan	4.00	43.40	16	2026-01-24 10:08:36	2026-01-27 14:54:12
120	16	501E	\N	\N	3	05	501E	9.78	9.78	6.20	SC	Weeding man.	Ringan	24-Jan	4.00	39.12	16	2026-01-24 10:08:36	2026-01-27 14:54:12
121	16	502D1	\N	\N	3	05	502D1	11.29	11.29	1.90	SC	Weeding man.	Ringan	24-Jan	4.00	45.16	16	2026-01-24 10:08:36	2026-01-27 14:54:12
122	16	502D2	\N	\N	3	05	502D2	7.40	7.40	3.10	SC	Weeding man.	Ringan	24-Jan	4.00	29.60	16	2026-01-24 10:08:36	2026-01-27 14:54:12
123	17	512B1	\N	\N	3	05	512B1	5.92	5.92	16.50	FC	Weeding man.	Ringan	18-Jan	3.00	17.76	16	2026-01-24 10:08:36	2026-01-27 14:54:12
124	17	512B2	\N	\N	3	05	512B2	6.39	6.39	16.50	FC	Weeding man.	Ringan	19-Jan	3.00	19.17	16	2026-01-24 10:08:36	2026-01-27 14:54:12
125	17	520A	\N	\N	3	05	520A	5.62	5.62	17.00	FC	Weeding man.	Ringan	19-Jan	3.00	16.86	16	2026-01-24 10:08:36	2026-01-27 14:54:12
126	17	513C3	\N	\N	3	05	513C3	9.18	9.18	2.50	BK	Weeding man.	Ringan	23-Jan	3.00	27.54	16	2026-01-24 10:08:36	2026-01-27 14:54:12
127	17	514D	\N	\N	3	05	514D	6.90	6.89	5.30	FC	Weeding man.	Ringan	19-Jan	3.00	20.67	16	2026-01-24 10:08:36	2026-01-27 14:54:12
130	17	525B1	\N	\N	3	05	525B1	10.95	10.31	8.90	FC	Weeding man.	Ringan	19-Jan	3.00	30.93	16	2026-01-24 10:08:36	2026-01-27 14:54:12
131	17	525B2	\N	\N	3	05	525B2	4.87	4.98	9.80	FC	Weeding man.	Ringan	19-Jan	3.00	14.94	16	2026-01-24 10:08:36	2026-01-27 14:54:12
132	18	530B1	\N	\N	3	05	530B1	10.68	10.68	4.80	FC	Weeding man.	Sedang	22-Jan	5.00	53.40	16	2026-01-24 10:08:36	2026-01-27 14:54:12
133	18	530B2	\N	\N	3	05	530B2	8.79	8.79	4.90	FC	Weeding man.	Sedang	23-Jan	5.00	43.95	16	2026-01-24 10:08:36	2026-01-27 14:54:12
134	18	530C	\N	\N	3	05	530C	10.21	10.21	7.50	FC	Weeding man.	Sedang	22-Jan	5.00	51.05	16	2026-01-24 10:08:36	2026-01-27 14:54:12
135	18	530D	\N	\N	3	05	530D	12.92	12.92	7.50	FC	Weeding man.	Sedang	22-Jan	5.00	64.60	16	2026-01-24 10:08:36	2026-01-27 14:54:12
136	18	531C3	\N	\N	3	05	531C3	9.94	9.94	6.40	FC	Weeding man.	Ringan	23-Jan	4.00	39.76	16	2026-01-24 10:08:36	2026-01-27 14:54:12
137	18	531G6	\N	\N	3	05	531G6	6.24	6.24	12.40	FC	Weeding man.	Ringan	18-Jan	4.00	24.96	16	2026-01-24 10:08:36	2026-01-27 14:54:12
138	18	536C1a	\N	\N	3	05	536C1a	4.46	4.46	13.00	FC	Weeding man.	Ringan	23-Jan	4.00	17.84	16	2026-01-24 10:08:36	2026-01-27 14:54:12
139	18	536C1c	\N	\N	3	05	536C1c	3.59	3.59	12.70	FC	Weeding man.	Ringan	18-Jan	4.00	14.36	16	2026-01-24 10:08:36	2026-01-27 14:54:12
140	18	537C	\N	\N	3	05	537C	9.20	9.20	2.60	SC	Weeding man.	Sedang	22-Jan	5.00	46.00	16	2026-01-24 10:08:36	2026-01-27 14:54:12
141	18	526A3	\N	\N	3	05	526A3	5.17	5.17	16.80	FC	Weeding man.	Ringan	20-Jan	4.00	20.68	16	2026-01-24 10:08:36	2026-01-27 14:54:12
143	18	528C2	\N	\N	3	05	528C2	9.10	9.10	7.50	SC	Weeding man.	Ringan	22-Jan	4.00	36.40	16	2026-01-24 10:08:36	2026-01-27 14:54:12
144	18	523E4	\N	\N	3	05	523E4	9.41	9.41	15.60	FC	Weeding man.	Ringan	21-Jan	3.00	28.23	16	2026-01-24 10:08:36	2026-01-27 14:54:12
145	19	541I2	\N	\N	3	05	541I2	11.23	11.23	5.20	FC	Weeding man.	Sedang	21-Jan	5.00	56.65	16	2026-01-24 10:08:36	2026-01-27 14:54:12
146	19	545A1	\N	\N	3	05	545A1	12.20	12.20	5.60	SC	Weeding man.	Ringan	21-Jan	4.00	48.80	16	2026-01-24 10:08:36	2026-01-27 14:54:12
147	19	546E1	\N	\N	3	05	546E1	7.08	7.08	1.90	SC	Weeding man.	Ringan	23-Jan	4.00	28.32	16	2026-01-24 10:08:36	2026-01-27 14:54:12
148	19	544C1	\N	\N	3	05	544C1	3.85	3.85	6.70	FC	Weeding man.	Sedang	20-Jan	5.00	19.25	16	2026-01-24 10:08:36	2026-01-27 14:54:12
149	19	544D2A	\N	\N	3	05	544D2A	4.09	4.09	5.30	FC	Weeding man.	Ringan	21-Jan	4.00	16.36	16	2026-01-24 10:08:36	2026-01-27 14:54:12
150	19	544D2B	\N	\N	3	05	544D2B	3.31	3.31	5.20	FC	Weeding man.	Ringan	21-Jan	4.00	13.24	16	2026-01-24 10:08:36	2026-01-27 14:54:12
151	19	543A1A	\N	\N	3	05	543A1A	5.89	5.89	3.50	SC	Weeding man.	Ringan	22-Jan	4.00	23.56	16	2026-01-24 10:08:36	2026-01-27 14:54:12
152	19	543A1B	\N	\N	3	05	543A1B	5.60	5.60	3.50	SC	Weeding man.	Ringan	21-Jan	4.00	22.40	16	2026-01-24 10:08:36	2026-01-27 14:54:12
153	19	553E1	\N	\N	3	05	553E1	11.40	11.40	5.00	FC	Weeding man.	Sedang	21-Jan	5.00	54.20	16	2026-01-24 10:08:36	2026-01-27 14:54:12
292	16	509C1	\N	\N	3	5	509C1	4.47	4.47	14.20	\N	Weeding man.	\N		4.00	17.88	12	2026-01-26 08:10:28	2026-01-26 08:10:28
293	16	509C2	\N	\N	3	5	509C2	4.43	4.43	14.10	\N	Weeding man.	\N		4.00	17.72	12	2026-01-26 08:10:28	2026-01-26 08:10:28
4	16	506A4	\N	\N	3	05	506A4	7.15	7.15	1.60	SC	Weeding man.	Ringan	8-Nov	4.00	28.60	18	2026-01-24 10:07:51	2026-01-28 13:37:47
45	19	545A2A	\N	\N	3	05	545A2A	7.01	7.82	12.10	FC	Weeding man.	Ringan	5-Nov	4.00	28.04	18	2026-01-24 10:07:51	2026-01-28 13:37:47
104	23	565B1	\N	\N	3	06	565B1	8.75	8.75	11.00	FC	Weeding man.	Ringan	7-Nov	3.00	26.25	18	2026-01-24 10:07:51	2026-01-28 13:37:48
105	23	565B2	\N	\N	3	06	565B2	7.31	7.31	11.10	FC	Weeding man.	Ringan	7-Nov	3.00	21.93	18	2026-01-24 10:07:51	2026-01-28 13:37:48
106	23	565A1	\N	\N	3	06	565A1	5.62	5.62	10.20	SC	Weeding man.	Ringan	7-Nov	3.00	16.86	18	2026-01-24 10:07:51	2026-01-28 13:37:48
108	23	561A2	\N	\N	3	06	561A2	10.43	10.43	3.60	SC	Weeding man.	Ringan	7-Nov	3.00	31.29	18	2026-01-24 10:07:51	2026-01-28 13:37:48
154	19	533H1	\N	\N	3	05	533H1	8.14	8.14	4.20	FC	Weeding man.	Ringan	22-Jan	3.00	23.01	16	2026-01-24 10:08:36	2026-01-27 14:54:12
156	19	534E1	\N	\N	3	05	534E1	11.84	11.84	7.00	FC	Weeding man.	Ringan	22-Jan	4.00	45.96	16	2026-01-24 10:08:36	2026-01-27 14:54:12
157	20	584B2	\N	\N	3	06	584B2	6.54	6.54	10.00	SC	Weeding man.	Ringan	18-Jan	4.00	26.16	16	2026-01-24 10:08:36	2026-01-27 14:54:12
158	20	583C	\N	\N	3	06	583C	11.55	11.55	2.50	SC	Weeding man.	Sedang	21-Jan	6.00	69.30	16	2026-01-24 10:08:36	2026-01-27 14:54:12
159	20	589D4	\N	\N	3	06	589D4	3.51	3.51	2.70	SC	Weeding man.	Sedang	22-Jan	6.00	21.06	16	2026-01-24 10:08:36	2026-01-27 14:54:12
160	20	585C	\N	\N	3	06	585C	10.67	10.67	1.60	SC	Weeding man.	Sedang	22-Jan	6.00	64.02	16	2026-01-24 10:08:36	2026-01-27 14:54:12
161	20	585B2	\N	\N	3	06	585B2	8.82	8.52	11.40	FC	Weeding man.	Ringan	22-Jan	3.00	25.56	16	2026-01-24 10:08:36	2026-01-27 14:54:12
162	20	585B1	\N	\N	3	06	585B1	4.28	4.06	11.60	FC	Weeding man.	Ringan	21-Jan	3.00	12.18	16	2026-01-24 10:08:36	2026-01-27 14:54:12
163	20	548G2C	\N	\N	3	06	548G2C	6.18	6.18	3.80	SC	Weeding man.	Ringan	21-Jan	3.00	18.54	16	2026-01-24 10:08:36	2026-01-27 14:54:12
164	20	538A2	\N	\N	3	06	538A2	5.32	5.32	14.40	FC	Weeding man.	Ringan	22-Jan	3.00	15.96	16	2026-01-24 10:08:36	2026-01-27 14:54:12
165	20	548H1	\N	\N	3	06	548H1	8.85	8.85	12.10	FC	Weeding man.	Ringan	22-Jan	3.00	26.55	16	2026-01-24 10:08:36	2026-01-27 14:54:12
166	20	586D2	\N	\N	3	06	586D2	6.44	6.44	13.40	FC	Weeding man.	Ringan	18-Jan	3.00	19.32	16	2026-01-24 10:08:36	2026-01-27 14:54:12
167	20	580C1	\N	\N	3	06	580C1	6.19	6.19	8.10	SC	Weeding man.	Ringan	21-Jan	4.00	24.76	16	2026-01-24 10:08:36	2026-01-27 14:54:12
168	20	586D1B	\N	\N	3	06	586D1B	3.84	3.84	1.10	SC	Weeding man.	Sedang	22-Jan	5.00	19.20	16	2026-01-24 10:08:36	2026-01-27 14:54:12
169	20	580C2	\N	\N	3	06	580C2	11.93	11.93	8.10	SC	Weeding man.	Sedang	22-Jan	5.00	59.65	16	2026-01-24 10:08:36	2026-01-27 14:54:12
170	20	579E	\N	\N	3	06	579E	9.78	9.17	3.60	FC	Weeding man.	Sedang	21-Jan	5.00	45.85	16	2026-01-24 10:08:36	2026-01-27 14:54:12
171	21	551F1A	\N	\N	3	06	551F1A	5.21	5.21	7.30	FC	Weeding man.	Ringan	24-Jan	4.00	20.84	16	2026-01-24 10:08:36	2026-01-27 14:54:12
172	21	551F1B	\N	\N	3	06	551F1B	4.81	4.56	7.40	FC	Weeding man.	Ringan	20-Jan	4.00	18.24	16	2026-01-24 10:08:36	2026-01-27 14:54:12
173	21	550D2A	\N	\N	3	06	550D2A	4.58	3.42	11.10	FC	Weeding man.	Ringan	18-Jan	3.00	10.26	16	2026-01-24 10:08:36	2026-01-27 14:54:12
174	21	550E2B	\N	\N	3	06	550E2B	6.04	6.04	8.00	FC	Weeding man.	Ringan	21-Jan	3.00	18.12	16	2026-01-24 10:08:36	2026-01-27 14:54:12
175	21	557D	\N	\N	3	06	557D	10.10	9.82	12.20	FC	Weeding man.	Ringan	21-Jan	3.00	29.46	16	2026-01-24 10:08:36	2026-01-27 14:54:12
176	21	549B2A	\N	\N	3	06	549B2A	4.84	4.78	3.50	FC	Weeding man.	Sedang	21-Jan	5.00	23.90	16	2026-01-24 10:08:36	2026-01-27 14:54:12
177	21	587C2	\N	\N	3	06	587C2	7.97	7.76	1.10	SC	Weeding man.	Ringan	21-Jan	3.00	23.28	16	2026-01-24 10:08:36	2026-01-27 14:54:12
178	21	554C1	\N	\N	3	06	554C1	11.61	11.03	5.10	FC	Weeding man.	Sedang	21-Jan	5.00	55.15	16	2026-01-24 10:08:36	2026-01-27 14:54:12
179	21	554C2	\N	\N	3	06	554C2	6.40	5.90	5.30	FC	Weeding man.	Sedang	21-Jan	5.00	29.50	16	2026-01-24 10:08:36	2026-01-27 14:54:12
180	21	552D1	\N	\N	3	06	552D1	6.50	6.18	0.90	FC	Weeding man.	Sedang	21-Jan	5.00	30.90	16	2026-01-24 10:08:36	2026-01-27 14:54:12
181	21	552F	\N	\N	3	06	552F	6.14	6.04	5.90	FC	Weeding man.	Sedang	21-Jan	5.00	30.20	16	2026-01-24 10:08:36	2026-01-27 14:54:12
182	21	551B3	\N	\N	3	06	551B3	4.45	4.45	4.20	FC	Weeding man.	Sedang	22-Jan	5.00	22.25	16	2026-01-24 10:08:36	2026-01-27 14:54:12
183	21	551B4	\N	\N	3	06	551B4	4.94	4.83	4.20	FC	Weeding man.	Sedang	21-Jan	5.00	24.15	16	2026-01-24 10:08:36	2026-01-27 14:54:12
184	21	556E2	\N	\N	3	06	556E2	6.50	6.68	3.90	FC	Weeding man.	Ringan	21-Jan	4.00	26.72	16	2026-01-24 10:08:36	2026-01-27 14:54:12
294	16	504B1A	\N	\N	3	5	504B1A	6.16	6.16	12.40	\N	Weeding man.	\N		4.00	24.64	12	2026-01-26 08:10:28	2026-01-26 08:10:28
295	16	505D1	\N	\N	3	5	505D1	4.88	4.88	7.70	\N	Weeding man.	\N		4.00	19.52	12	2026-01-26 08:10:28	2026-01-26 08:10:28
185	21	562A1	\N	\N	3	06	562A1	7.64	7.26	4.70	FC	Weeding man.	Ringan	24-Jan	4.00	29.04	16	2026-01-24 10:08:36	2026-01-27 14:54:12
186	21	562A2	\N	\N	3	06	562A2	15.78	14.39	4.90	FC	Weeding man.	Ringan	21-Jan	4.00	57.56	16	2026-01-24 10:08:36	2026-01-27 14:54:12
187	21	562F1	\N	\N	3	06	562F1	5.56	5.13	4.60	FC	Weeding man.	Ringan	21-Jan	4.00	20.52	16	2026-01-24 10:08:36	2026-01-27 14:54:12
189	21	562B1	\N	\N	3	06	562B1	9.04	8.74	2.50	FC	Weeding man.	Ringan	22-Jan	4.00	34.96	16	2026-01-24 10:08:36	2026-01-27 14:54:12
190	22	569A1	\N	\N	3	06	569A1	9.22	10.96	1.60	FC	Weeding man.	Ringan	22-Jan	3.00	32.88	16	2026-01-24 10:08:36	2026-01-27 14:54:12
192	22	570G2	\N	\N	3	06	570G2	8.39	10.48	2.50	ST	Weeding man.	Ringan	22-Jan	3.00	31.44	16	2026-01-24 10:08:36	2026-01-27 14:54:12
193	22	568C1	\N	\N	3	06	568C1	6.07	6.07	6.10	SC	Weeding man.	Ringan	21-Jan	3.00	18.21	16	2026-01-24 10:08:36	2026-01-27 14:54:12
194	22	568C2	\N	\N	3	06	568C2	10.60	10.60	3.70	SC	Weeding man.	Ringan	22-Jan	3.00	31.80	16	2026-01-24 10:08:36	2026-01-27 14:54:12
195	22	568C3	\N	\N	3	06	568C3	7.22	7.22	5.80	SC	Weeding man.	Ringan	21-Jan	3.00	21.66	16	2026-01-24 10:08:36	2026-01-27 14:54:12
196	22	567C1B	\N	\N	3	06	567C1B	7.33	7.33	0.50	FC	Weeding man.	Ringan	22-Jan	3.00	21.99	16	2026-01-24 10:08:36	2026-01-27 14:54:12
197	22	570E	\N	\N	3	06	570E	12.68	12.68	0.80	FC	Weeding man.	Ringan	22-Jan	3.00	38.04	16	2026-01-24 10:08:36	2026-01-27 14:54:12
198	22	560D2	\N	\N	3	06	560D2	6.20	6.20	15.60	FC	Weeding man.	Ringan	21-Jan	3.00	18.60	16	2026-01-24 10:08:36	2026-01-27 14:54:12
199	22	560D3	\N	\N	3	06	560D3	5.76	5.76	15.00	FC	Weeding man.	Ringan	22-Jan	3.00	17.28	16	2026-01-24 10:08:36	2026-01-27 14:54:12
200	22	559C2	\N	\N	3	06	559C2	5.11	5.11	11.50	SC	Weeding man.	Ringan	21-Jan	3.00	15.33	16	2026-01-24 10:08:36	2026-01-27 14:54:12
201	22	567E2A	\N	\N	3	06	567E2A	6.36	6.36	0.80	FC	Weeding man.	Bersih	18-Jan	2.00	12.72	16	2026-01-24 10:08:36	2026-01-27 14:54:12
54	20	584A1	\N	\N	3	06	584A1	5.48	5.48	6.10	SC	Weeding man.	Ringan	30-Oct	3.00	16.44	18	2026-01-24 10:07:51	2026-01-28 13:37:47
58	20	588A	\N	\N	3	06	588A	9.45	9.45	0.80	FC	Weeding man.	Ringan	29-Oct	3.00	28.35	18	2026-01-24 10:07:51	2026-01-28 13:37:47
202	22	567E2B	\N	\N	3	06	567E2B	5.69	5.69	0.70	FC	Weeding man.	Bersih	21-Jan	2.00	11.38	16	2026-01-24 10:08:36	2026-01-27 14:54:12
204	23	555B	\N	\N	3	06	555B	6.85	6.85	5.80	FC	Weeding man.	Ringan	23-Jan	4.00	27.40	16	2026-01-24 10:08:36	2026-01-27 14:54:12
205	23	554G2	\N	\N	3	06	554G2	4.71	4.71	1.00	SC	Weeding man.	Ringan	23-Jan	4.00	18.84	16	2026-01-24 10:08:36	2026-01-27 14:54:12
206	23	554B2	\N	\N	3	06	554B2	12.74	12.74	2.20	FC	Weeding man.	Ringan	23-Jan	3.00	38.22	16	2026-01-24 10:08:36	2026-01-27 14:54:12
208	23	573A1	\N	\N	3	06	573A1	9.55	9.55	13.30	FC	Weeding man.	Ringan	23-Jan	4.00	38.20	16	2026-01-24 10:08:36	2026-01-27 14:54:12
209	23	573B2	\N	\N	3	06	573B2	8.63	8.63	12.80	FC	Weeding man.	Ringan	23-Jan	4.00	34.52	16	2026-01-24 10:08:36	2026-01-27 14:54:12
210	23	565E1	\N	\N	3	06	565E1	12.28	12.28	6.60	FC	Weeding man.	Ringan	23-Jan	3.00	36.84	16	2026-01-24 10:08:36	2026-01-27 14:54:12
211	23	561A1	\N	\N	3	06	561A1	10.15	10.15	6.40	SC	Weeding man.	Ringan	23-Jan	3.00	30.45	16	2026-01-24 10:08:36	2026-01-27 14:54:12
212	23	566A	\N	\N	3	06	566A	6.93	6.93	14.10	FC	Weeding man.	Ringan	23-Jan	3.00	20.79	16	2026-01-24 10:08:36	2026-01-27 14:54:12
213	23	561D	\N	\N	3	06	561D	6.30	6.30	1.30	SC	Weeding man.	Ringan	23-Jan	3.00	18.90	16	2026-01-24 10:08:36	2026-01-27 14:54:12
214	23	572D2A	\N	\N	3	06	572D2A	5.40	5.40	4.20	FC	Weeding man.	Bersih	23-Jan	2.00	10.80	16	2026-01-24 10:08:36	2026-01-27 14:54:12
215	23	572D2B	\N	\N	3	06	572D2B	6.41	6.41	4.30	FC	Weeding man.	Bersih	23-Jan	2.00	12.82	16	2026-01-24 10:08:36	2026-01-27 14:54:12
296	16	505D2	\N	\N	3	5	505D2	7.65	7.65	7.60	\N	Weeding man.	\N		4.00	30.60	12	2026-01-26 08:10:28	2026-01-26 08:10:28
216	16	507B4	\N	\N	3	05	507B4	2.83	2.83	7.60	FC	Weeding man.	Ringan	9-Nov	4.00	11.32	3	2026-01-24 10:09:05	2026-01-24 10:09:05
217	16	507B3	\N	\N	3	05	507B3	2.79	2.79	7.70	FC	Weeding man.	Ringan	10-Nov	4.00	11.16	3	2026-01-24 10:09:05	2026-01-24 10:09:05
218	16	507B2	\N	\N	3	05	507B2	3.03	3.03	7.70	FC	Weeding man.	Ringan	11-Nov	4.00	12.12	3	2026-01-24 10:09:05	2026-01-24 10:09:05
219	16	503A2	\N	\N	3	05	503A2	6.12	6.12	12.40	FC	Weeding man.	Ringan	15-Nov	4.00	24.48	3	2026-01-24 10:09:05	2026-01-24 10:09:05
220	16	509A4	\N	\N	3	05	509A4	6.35	6.35	8.90	FC	Weeding man.	Ringan	15-Nov	4.00	25.40	3	2026-01-24 10:09:05	2026-01-24 10:09:05
221	16	502E	\N	\N	3	05	502E	7.24	7.24	11.40	FC	Weeding man.	Ringan	15-Nov	4.00	28.96	3	2026-01-24 10:09:05	2026-01-24 10:09:05
222	16	501A	\N	\N	3	05	501A	7.89	7.89	2.70	SC	Weeding man.	Ringan	15-Nov	4.00	31.56	3	2026-01-24 10:09:05	2026-01-24 10:09:05
223	17	519E3B	\N	\N	3	05	519E3B	6.86	6.86	15.70	FC	Weeding man.	Ringan	14-Nov	4.00	27.44	3	2026-01-24 10:09:05	2026-01-24 10:09:05
224	17	520D2	\N	\N	3	05	520D2	9.24	8.85	5.40	FC	Weeding man.	Ringan	9-Nov	4.00	35.40	3	2026-01-24 10:09:05	2026-01-24 10:09:05
225	17	520F	\N	\N	3	05	520F	12.09	11.45	6.10	FC	Weeding man.	Ringan	10-Nov	3.00	34.35	3	2026-01-24 10:09:05	2026-01-24 10:09:05
226	17	514G1	\N	\N	3	05	514G1	5.03	4.33	7.50	FC	Weeding man.	Ringan	10-Nov	3.00	12.99	3	2026-01-24 10:09:05	2026-01-24 10:09:05
227	17	515F1	\N	\N	3	05	515F1	6.04	5.50	8.20	FC	Weeding man.	Ringan	10-Nov	3.00	16.50	3	2026-01-24 10:09:05	2026-01-24 10:09:05
228	17	518G	\N	\N	3	05	518G	13.23	13.07	7.60	FC	Weeding man.	Ringan	14-Nov	3.00	39.21	3	2026-01-24 10:09:05	2026-01-24 10:09:05
297	16	508D	\N	\N	3	5	508D	11.06	11.06	5.80	\N	Weeding man.	\N		4.00	44.24	12	2026-01-26 08:10:28	2026-01-26 08:10:28
229	17	520D1	\N	\N	3	05	520D1	4.30	4.30	1.90	SC	Weeding man.	Ringan	10-Nov	4.00	17.20	3	2026-01-24 10:09:05	2026-01-24 10:09:05
230	18	519P	\N	\N	3	05	519P	5.67	5.67	12.30	FC	Weeding man.	Ringan	14-Nov	4.00	22.68	3	2026-01-24 10:09:05	2026-01-24 10:09:05
231	18	519Q	\N	\N	3	05	519Q	10.19	10.19	12.60	FC	Weeding man.	Ringan	14-Nov	4.00	40.76	3	2026-01-24 10:09:05	2026-01-24 10:09:05
232	18	519R	\N	\N	3	05	519R	7.41	7.41	9.90	FC	Weeding man.	Ringan	9-Nov	4.00	29.64	3	2026-01-24 10:09:05	2026-01-24 10:09:05
234	18	536D2	\N	\N	3	05	536D2	6.54	6.54	7.50	FC	Weeding man.	Ringan	13-Nov	4.00	26.16	3	2026-01-24 10:09:05	2026-01-24 10:09:05
235	18	526B2	\N	\N	3	05	526B2	8.68	8.68	17.20	FC	Weeding man.	Ringan	14-Nov	4.00	34.72	3	2026-01-24 10:09:05	2026-01-24 10:09:05
236	18	526A2	\N	\N	3	05	526A2	9.75	9.75	15.40	FC	Weeding man.	Ringan	9-Nov	4.00	39.00	3	2026-01-24 10:09:05	2026-01-24 10:09:05
237	18	526B1	\N	\N	3	05	526B1	3.80	3.80	0.70	SC	Weeding man.	Ringan	13-Nov	4.00	15.20	3	2026-01-24 10:09:05	2026-01-24 10:09:05
238	18	526A1	\N	\N	3	05	526A1	7.92	7.92	1.90	SC	Weeding man.	Ringan	14-Nov	4.00	31.68	3	2026-01-24 10:09:05	2026-01-24 10:09:05
239	18	529A1	\N	\N	3	05	529A1	5.49	5.49	6.80	SC	Weeding man.	Ringan	9-Nov	4.00	21.96	3	2026-01-24 10:09:05	2026-01-24 10:09:05
240	18	529A2	\N	\N	3	05	529A2	9.06	9.06	6.70	SC	Weeding man.	Ringan	13-Nov	4.00	36.24	3	2026-01-24 10:09:05	2026-01-24 10:09:05
241	19	545E2	\N	\N	3	05	545E2	5.04	5.04	9.00	FC	Weeding man.	Sedang	12-Nov	5.00	25.20	3	2026-01-24 10:09:05	2026-01-24 10:09:05
242	19	544B4	\N	\N	3	05	544B4	13.56	13.56	3.40	FC	Weeding man.	Sedang	14-Nov	6.00	81.36	3	2026-01-24 10:09:05	2026-01-24 10:09:05
96	22	558I2	\N	\N	3	06	558I2	8.39	8.39	6.10	SC	Weeding man.	Ringan	2-Nov	3.00	25.17	18	2026-01-24 10:07:51	2026-01-28 13:37:48
244	19	533B	\N	\N	3	05	533B	12.13	12.13	16.20	FC	Weeding man.	Ringan	12-Nov	3.00	36.39	3	2026-01-24 10:09:05	2026-01-24 10:09:05
245	19	546E2	\N	\N	3	05	546E2	5.66	5.66	1.60	SC	Weeding man.	Sedang	11-Nov	5.00	28.30	3	2026-01-24 10:09:05	2026-01-24 10:09:05
246	19	532A2	\N	\N	3	05	532A2	7.18	7.18	10.60	SC	Weeding man.	Ringan	13-Nov	3.00	21.54	3	2026-01-24 10:09:05	2026-01-24 10:09:05
247	19	533E2	\N	\N	3	05	533E2	7.81	7.81	7.50	SC	Weeding man.	Ringan	13-Nov	3.00	23.43	3	2026-01-24 10:09:05	2026-01-24 10:09:05
248	19	533C2	\N	\N	3	05	533C2	7.58	7.58	10.40	SC	Weeding man.	Ringan	12-Nov	3.00	23.40	3	2026-01-24 10:09:05	2026-01-24 10:09:05
249	20	539A	\N	\N	3	06	539A	8.17	7.61	5.70	FC	Weeding man.	Ringan	9-Nov	3.00	22.83	3	2026-01-24 10:09:05	2026-01-24 10:09:05
250	20	548G1A	\N	\N	3	06	548G1A	4.09	4.09	12.70	FC	Weeding man.	Ringan	9-Nov	3.00	12.27	3	2026-01-24 10:09:05	2026-01-24 10:09:05
251	20	548G1B	\N	\N	3	06	548G1B	4.46	4.46	12.60	FC	Weeding man.	Ringan	12-Nov	3.00	13.38	3	2026-01-24 10:09:05	2026-01-24 10:09:05
103	23	573D	\N	\N	3	06	573D	13.88	13.88	3.10	FC	Weeding man.	Ringan	7-Nov	3.00	41.64	18	2026-01-24 10:07:51	2026-01-28 13:37:48
243	19	544C3	\N	\N	3	5	544C3	4.52	4.52	5.10	\N	Weeding man.	\N		6.00	27.12	12	2026-01-24 10:09:05	2026-01-26 08:10:28
252	20	586D1A	\N	\N	3	6	586D1A	11.20	11.20	14.60	\N	Weeding man.	\N		5.00	56.00	12	2026-01-24 10:09:05	2026-01-26 08:10:28
253	20	586B	\N	\N	3	06	586B	8.15	12.74	4.10	FC	Weeding man.	Ringan	13-Nov	4.00	50.96	3	2026-01-24 10:09:05	2026-01-24 10:09:05
254	20	538C2	\N	\N	3	06	538C2	10.41	10.41	13.60	FC	Weeding man.	Ringan	12-Nov	3.00	31.23	3	2026-01-24 10:09:05	2026-01-24 10:09:05
255	20	589AS1	\N	\N	3	06	589AS1	7.81	8.47	4.50	FC	Weeding man.	Sedang	13-Nov	5.00	42.35	3	2026-01-24 10:09:05	2026-01-24 10:09:05
256	20	547D2	\N	\N	3	06	547D2	7.23	7.23	3.00	SC	Weeding man.	Ringan	13-Nov	3.00	21.69	3	2026-01-24 10:09:05	2026-01-24 10:09:05
257	20	589A1	\N	\N	3	06	589A1	5.48	5.48	10.90	SC	Weeding man.	Sedang	12-Nov	6.00	32.88	3	2026-01-24 10:09:05	2026-01-24 10:09:05
258	20	582B2	\N	\N	3	06	582B2	8.95	8.95	11.50	SC	Weeding man.	Sedang	12-Nov	6.00	53.70	3	2026-01-24 10:09:05	2026-01-24 10:09:05
142	18	535C	\N	\N	3	05	535C	9.15	9.15	3.50	FC	Weeding man.	Ringan	21-Jan	4.00	36.60	16	2026-01-24 10:08:36	2026-01-27 14:54:12
188	21	562F2	\N	\N	3	06	562F2	9.92	9.53	4.40	FC	Weeding man.	Ringan	21-Jan	4.00	38.12	16	2026-01-24 10:08:36	2026-01-27 14:54:12
203	22	558A2	\N	\N	3	06	558A2	6.56	6.56	0.50	FC	Weeding man.	Bersih	20-Jan	2.00	13.12	16	2026-01-24 10:08:36	2026-01-27 14:54:12
207	23	563B1	\N	\N	3	06	563B1	6.76	6.76	2.50	FC	Weeding man.	Ringan	23-Jan	3.00	20.28	16	2026-01-24 10:08:36	2026-01-27 14:54:12
264	21	556A	\N	\N	3	06	556A	12.54	12.29	8.40	FC	Weeding man.	Ringan	11-Nov	3.00	36.87	3	2026-01-24 10:09:05	2026-01-24 10:09:05
265	21	550E2A	\N	\N	3	06	550E2A	4.35	4.35	6.00	FC	Weeding man.	Ringan	12-Nov	3.00	13.05	3	2026-01-24 10:09:05	2026-01-24 10:09:05
266	21	550E1B	\N	\N	3	06	550E1B	6.27	7.58	13.90	FC	Weeding man.	Ringan	12-Nov	3.00	22.74	3	2026-01-24 10:09:05	2026-01-24 10:09:05
267	21	551E1	\N	\N	3	06	551E1	5.82	5.82	5.50	FC	Weeding man.	Ringan	12-Nov	4.00	23.28	3	2026-01-24 10:09:05	2026-01-24 10:09:05
268	21	551E2	\N	\N	3	06	551E2	4.62	4.62	5.60	FC	Weeding man.	Ringan	12-Nov	4.00	18.48	3	2026-01-24 10:09:05	2026-01-24 10:09:05
269	21	551D1	\N	\N	3	06	551D1	5.26	5.81	6.00	SC	Weeding man.	Ringan	13-Nov	3.00	17.43	3	2026-01-24 10:09:05	2026-01-24 10:09:05
270	21	551D2	\N	\N	3	06	551D2	10.00	10.37	5.00	SC	Weeding man.	Ringan	13-Nov	4.00	41.48	3	2026-01-24 10:09:05	2026-01-24 10:09:05
271	21	551D3	\N	\N	3	06	551D3	7.96	8.29	4.40	SC	Weeding man.	Ringan	12-Nov	4.00	33.16	3	2026-01-24 10:09:05	2026-01-24 10:09:05
272	21	550G	\N	\N	3	06	550G	5.03	5.24	5.00	SC	Weeding man.	Sedang	12-Nov	5.00	26.20	3	2026-01-24 10:09:05	2026-01-24 10:09:05
273	21	587C1	\N	\N	3	06	587C1	2.96	2.74	2.20	SC	Weeding man.	Sedang	12-Nov	5.00	13.70	3	2026-01-24 10:09:05	2026-01-24 10:09:05
274	21	551H	\N	\N	3	06	551H	10.94	10.94	10.40	SC	Weeding man.	Sedang	12-Nov	5.00	54.70	3	2026-01-24 10:09:05	2026-01-24 10:09:05
275	22	567F1	\N	\N	3	06	567F1	6.85	6.85	8.70	FC	Weeding man.	Ringan	11-Nov	3.00	20.55	3	2026-01-24 10:09:05	2026-01-24 10:09:05
276	22	567F2	\N	\N	3	06	567F2	5.38	5.38	8.60	FC	Weeding man.	Ringan	13-Nov	3.00	16.14	3	2026-01-24 10:09:05	2026-01-24 10:09:05
277	22	567F3	\N	\N	3	06	567F3	6.55	6.55	8.70	FC	Weeding man.	Ringan	12-Nov	3.00	19.65	3	2026-01-24 10:09:05	2026-01-24 10:09:05
278	22	569C2	\N	\N	3	06	569C2	10.62	10.62	4.60	FC	Weeding man.	Ringan	9-Nov	3.00	31.86	3	2026-01-24 10:09:05	2026-01-24 10:09:05
279	22	558D4	\N	\N	3	06	558D4	9.06	9.06	11.40	SC	Weeding man.	Ringan	12-Nov	3.00	27.18	3	2026-01-24 10:09:05	2026-01-24 10:09:05
280	22	558G1	\N	\N	3	06	558G1	9.19	9.19	10.40	SC	Weeding man.	Ringan	9-Nov	3.00	27.57	3	2026-01-24 10:09:05	2026-01-24 10:09:05
281	23	555C3	\N	\N	3	06	555C3	8.96	8.96	4.30	FC	Weeding man.		14-Nov	2.00	17.92	3	2026-01-24 10:09:05	2026-01-24 10:09:05
282	23	554F5	\N	\N	3	06	554F5	7.08	7.08	8.00	FC	Weeding man.		14-Nov	3.00	21.24	3	2026-01-24 10:09:05	2026-01-24 10:09:05
283	23	565D1	\N	\N	3	06	565D1	8.89	8.89	3.50	FC	Weeding man.		14-Nov	2.00	17.78	3	2026-01-24 10:09:05	2026-01-24 10:09:05
284	23	565D2	\N	\N	3	06	565D2	8.76	8.76	3.80	FC	Weeding man.		14-Nov	2.00	17.52	3	2026-01-24 10:09:05	2026-01-24 10:09:05
40	18	529G	\N	\N	3	05	529G	7.04	7.04	3.40	SC	Weeding man.	Ringan	2-Nov	4.00	28.16	18	2026-01-24 10:07:51	2026-01-28 13:37:47
288	23	561F1	\N	\N	3	06	561F1	7.39	7.39	8.50	SC	Weeding man.		9-Nov	3.00	22.17	3	2026-01-24 10:09:05	2026-01-24 10:09:05
289	23	564A1	\N	\N	3	06	564A1	4.89	5.04	1.30	ST	Weeding man.		14-Nov	3.00	15.12	3	2026-01-24 10:09:05	2026-01-24 10:09:05
298	16	504A2	\N	\N	3	5	504A2	3.85	3.85	9.60	\N	Weeding man.	\N		4.00	15.40	12	2026-01-26 08:10:28	2026-01-26 08:10:28
299	17	512E	\N	\N	3	5	512E	12.57	11.70	3.50	\N	Weeding man.	\N		4.00	46.80	12	2026-01-26 08:10:28	2026-01-26 08:10:28
300	17	512H	\N	\N	3	5	512H	10.10	9.88	4.10	\N	Weeding man.	\N		4.00	39.52	12	2026-01-26 08:10:28	2026-01-26 08:10:28
301	17	520G2	\N	\N	3	5	520G2	4.28	3.51	9.20	\N	Weeding man.	\N		4.00	14.04	12	2026-01-26 08:10:28	2026-01-26 08:10:28
302	17	513B	\N	\N	3	5	513B	3.71	3.71	15.70	\N	Weeding man.	\N		3.00	11.13	12	2026-01-26 08:10:28	2026-01-26 08:10:28
303	17	518C1	\N	\N	3	5	518C1	7.67	7.47	3.70	\N	Weeding man.	\N		3.00	22.41	12	2026-01-26 08:10:28	2026-01-26 08:10:28
304	17	518C2	\N	\N	3	5	518C2	8.74	8.76	3.70	\N	Weeding man.	\N		3.00	26.28	12	2026-01-26 08:10:28	2026-01-26 08:10:28
305	17	513A1	\N	\N	3	5	513A1	9.55	9.55	10.30	\N	Weeding man.	\N		3.00	28.65	12	2026-01-26 08:10:28	2026-01-26 08:10:28
306	17	514H1	\N	\N	3	5	514H1	7.09	7.09	3.60	\N	Weeding man.	\N		3.00	21.27	12	2026-01-26 08:10:28	2026-01-26 08:10:28
307	18	521G1a	\N	\N	3	5	521G1a	4.99	4.99	6.60	\N	Weeding man.	\N		5.00	24.95	12	2026-01-26 08:10:28	2026-01-26 08:10:28
308	18	521G1b	\N	\N	3	5	521G1b	7.56	7.56	6.30	\N	Weeding man.	\N		5.00	37.80	12	2026-01-26 08:10:28	2026-01-26 08:10:28
262	21	557F1	\N	\N	3	6	557F1	3.89	3.21	3.30	\N	Weeding man.	\N		4.00	12.84	12	2026-01-24 10:09:05	2026-01-26 08:10:28
263	21	557F2	\N	\N	3	6	557F2	5.53	4.46	3.10	\N	Weeding man.	\N		4.00	17.84	12	2026-01-24 10:09:05	2026-01-26 08:10:28
260	21	557A1A	\N	\N	3	6	557A1A	6.28	6.50	3.00	\N	Weeding man.	\N		4.00	26.00	12	2026-01-24 10:09:05	2026-01-26 08:10:28
261	21	557A1B	\N	\N	3	6	557A1B	7.13	6.28	2.50	\N	Weeding man.	\N		4.00	25.12	12	2026-01-24 10:09:05	2026-01-26 08:10:28
285	23	572C	\N	\N	3	6	572C	13.44	13.44	11.00	\N	Weeding man.	\N		4.00	53.76	12	2026-01-24 10:09:05	2026-01-26 08:10:29
286	23	571E	\N	\N	3	6	571E	11.24	11.24	13.10	\N	Weeding man.	\N		3.00	33.72	12	2026-01-24 10:09:05	2026-01-26 08:10:29
287	23	565A2	\N	\N	3	6	565A2	9.09	9.09	10.20	\N	Weeding man.	\N		3.00	27.27	12	2026-01-24 10:09:05	2026-01-26 08:10:29
309	18	531C1	\N	\N	3	5	531C1	4.32	4.32	10.20	\N	Weeding man.	\N		5.00	21.60	12	2026-01-26 08:10:28	2026-01-26 08:10:28
233	18	536B	\N	\N	3	5	536B	10.03	10.03	7.10	\N	Weeding man.	\N		5.00	50.15	12	2026-01-24 10:09:05	2026-01-26 08:10:28
310	18	5.28E+03	\N	\N	3	5	5.28E+03	8.98	8.98	3.40	\N	Weeding man.	\N		5.00	44.90	12	2026-01-26 08:10:28	2026-01-26 08:10:28
311	18	5.28E+04	\N	\N	3	5	5.28E+04	9.77	9.77	3.60	\N	Weeding man.	\N		5.00	48.85	12	2026-01-26 08:10:28	2026-01-26 08:10:28
312	18	531A	\N	\N	3	5	531A	9.99	9.99	10.80	\N	Weeding man.	\N		5.00	49.95	12	2026-01-26 08:10:28	2026-01-26 08:10:28
313	18	528F	\N	\N	3	5	528F	9.08	9.08	3.80	\N	Weeding man.	\N		5.00	45.40	12	2026-01-26 08:10:28	2026-01-26 08:10:28
314	19	546D	\N	\N	3	5	546D	8.76	8.76	6.50	\N	Weeding man.	\N		4.00	32.28	12	2026-01-26 08:10:28	2026-01-26 08:10:28
315	19	546B	\N	\N	3	5	546B	7.94	7.94	13.60	\N	Weeding man.	\N		4.00	31.76	12	2026-01-26 08:10:28	2026-01-26 08:10:28
316	19	544C2	\N	\N	3	5	544C2	2.90	2.90	5.10	\N	Weeding man.	\N		6.00	17.40	12	2026-01-26 08:10:28	2026-01-26 08:10:28
317	19	5.43E+03	\N	\N	3	5	5.43E+03	7.94	7.94	3.00	\N	Weeding man.	\N		6.00	45.54	12	2026-01-26 08:10:28	2026-01-26 08:10:28
318	19	5.43E+04	\N	\N	3	5	5.43E+04	3.91	3.91	2.90	\N	Weeding man.	\N		6.00	22.50	12	2026-01-26 08:10:28	2026-01-26 08:10:28
319	19	532B	\N	\N	3	5	532B	7.68	7.68	7.70	\N	Weeding man.	\N		3.00	23.04	12	2026-01-26 08:10:28	2026-01-26 08:10:28
320	19	534C	\N	\N	3	5	534C	14.24	14.24	4.80	\N	Weeding man.	\N		4.00	52.44	12	2026-01-26 08:10:28	2026-01-26 08:10:28
128	17	514F2	\N	\N	3	05	514F2	7.57	6.96	1.30	ST	Weeding man.	Ringan	23-Jan	4.00	27.84	16	2026-01-24 10:08:36	2026-01-27 14:54:12
321	19	534G2	\N	\N	3	5	534G2	5.91	5.91	5.70	\N	Weeding man.	\N		4.00	22.52	12	2026-01-26 08:10:28	2026-01-26 08:10:28
322	19	5.34E+03	\N	\N	3	5	5.34E+03	11.84	11.84	6.10	\N	Weeding man.	\N		4.00	45.96	12	2026-01-26 08:10:28	2026-01-26 08:10:28
323	20	582C	\N	\N	3	6	582C	6.41	6.09	2.30	\N	Weeding man.	\N		3.00	18.27	12	2026-01-26 08:10:28	2026-01-26 08:10:28
324	20	588B1	\N	\N	3	6	588B1	8.46	7.58	1.30	\N	Weeding man.	\N		3.00	22.74	12	2026-01-26 08:10:28	2026-01-26 08:10:28
259	20	588B2	\N	\N	3	6	588B2	10.32	9.42	1.50	\N	Weeding man.	\N		3.00	28.26	12	2026-01-24 10:09:05	2026-01-26 08:10:28
325	20	5.84E+05	\N	\N	3	6	5.84E+05	10.27	4.28	4.50	\N	Weeding man.	\N		4.00	17.12	12	2026-01-26 08:10:28	2026-01-26 08:10:28
326	20	547F1	\N	\N	3	6	547F1	6.21	5.89	1.20	\N	Weeding man.	\N		3.00	17.67	12	2026-01-26 08:10:28	2026-01-26 08:10:28
327	20	547F2	\N	\N	3	6	547F2	5.53	5.39	1.10	\N	Weeding man.	\N		3.00	16.17	12	2026-01-26 08:10:28	2026-01-26 08:10:28
328	21	5.56E+04	\N	\N	3	6	5.56E+04	6.50	6.68	2.90	\N	Weeding man.	\N		5.00	33.40	12	2026-01-26 08:10:28	2026-01-26 08:10:28
329	21	557D1	\N	\N	3	6	557D1	7.93	7.84	11.80	\N	Weeding man.	\N		4.00	31.36	12	2026-01-26 08:10:28	2026-01-26 08:10:28
330	21	557D2	\N	\N	3	6	557D2	9.99	9.53	15.40	\N	Weeding man.	\N		4.00	38.12	12	2026-01-26 08:10:28	2026-01-26 08:10:28
331	21	557F3	\N	\N	3	6	557F3	4.63	4.79	12.50	\N	Weeding man.	\N		4.00	19.16	12	2026-01-26 08:10:28	2026-01-26 08:10:28
332	21	557A2	\N	\N	3	6	557A2	8.08	7.08	2.20	\N	Weeding man.	\N		4.00	28.32	12	2026-01-26 08:10:28	2026-01-26 08:10:28
333	21	548A1	\N	\N	3	6	548A1	7.61	8.17	6.00	\N	Weeding man.	\N		5.00	40.85	12	2026-01-26 08:10:28	2026-01-26 08:10:28
334	21	548A2	\N	\N	3	6	548A2	10.91	11.65	6.00	\N	Weeding man.	\N		5.00	58.25	12	2026-01-26 08:10:28	2026-01-26 08:10:28
335	21	587F	\N	\N	3	6	587F	8.62	9.10	7.30	\N	Weeding man.	\N		5.00	45.50	12	2026-01-26 08:10:28	2026-01-26 08:10:28
336	21	553A	\N	\N	3	6	553A	4.43	4.56	9.30	\N	Weeding man.	\N		5.00	22.80	12	2026-01-26 08:10:28	2026-01-26 08:10:28
337	21	552E	\N	\N	3	6	552E	8.88	8.80	3.70	\N	Weeding man.	\N		5.00	44.00	12	2026-01-26 08:10:28	2026-01-26 08:10:28
338	22	567D2	\N	\N	3	6	567D2	10.26	10.26	15.80	\N	Weeding man.	\N		3.00	30.78	12	2026-01-26 08:10:28	2026-01-26 08:10:28
339	22	560A	\N	\N	3	6	560A	7.49	7.49	5.10	\N	Weeding man.	\N		3.00	22.47	12	2026-01-26 08:10:29	2026-01-26 08:10:29
340	23	564B1	\N	\N	3	6	564B1	7.56	7.56	10.90	\N	Weeding man.	\N		3.00	22.68	12	2026-01-26 08:10:29	2026-01-26 08:10:29
341	23	564B2	\N	\N	3	6	564B2	8.08	8.08	10.80	\N	Weeding man.	\N		3.00	24.24	12	2026-01-26 08:10:29	2026-01-26 08:10:29
342	23	564C	\N	\N	3	6	564C	10.86	10.86	11.00	\N	Weeding man.	\N		3.00	32.58	12	2026-01-26 08:10:29	2026-01-26 08:10:29
343	23	5.66E+04	\N	\N	3	6	5.66E+04	8.13	8.13	5.50	\N	Weeding man.	\N		3.00	24.39	12	2026-01-26 08:10:29	2026-01-26 08:10:29
344	23	5.65E+03	\N	\N	3	6	5.65E+03	12.28	12.28	5.70	\N	Weeding man.	\N		3.00	36.84	12	2026-01-26 08:10:29	2026-01-26 08:10:29
345	23	573B1	\N	\N	3	6	573B1	10.00	10.00	12.40	\N	Weeding man.	\N		3.00	30.00	12	2026-01-26 08:10:29	2026-01-26 08:10:29
346	23	5.65E+04	\N	\N	3	6	5.65E+04	8.89	8.89	2.50	\N	Weeding man.	\N		2.00	17.78	12	2026-01-26 08:10:29	2026-01-26 08:10:29
347	23	566D1	\N	\N	3	6	566D1	7.68	7.68	10.30	\N	Weeding man.	\N		2.00	15.36	12	2026-01-26 08:10:29	2026-01-26 08:10:29
348	23	563C2	\N	\N	3	6	563C2	10.58	10.58	9.30	\N	Weeding man.	\N		3.00	31.74	12	2026-01-26 08:10:29	2026-01-26 08:10:29
349	23	572B1	\N	\N	3	6	572B1	3.22	3.22	5.70	\N	Weeding man.	\N		3.00	9.66	12	2026-01-26 08:10:29	2026-01-26 08:10:29
350	23	563C1	\N	\N	3	6	563C1	11.60	11.60	10.90	\N	Weeding man.	\N		3.00	34.80	12	2026-01-26 08:10:29	2026-01-26 08:10:29
351	23	572A2	\N	\N	3	6	572A2	11.26	11.26	12.10	\N	Weeding man.	\N		4.00	45.04	12	2026-01-26 08:10:29	2026-01-26 08:10:29
56	20	582A1	\N	\N	3	06	582A1	7.71	7.71	1.40	FC	Weeding man.	Ringan	29-Oct	3.00	23.13	18	2026-01-24 10:07:51	2026-01-28 13:37:47
78	21	549B2B	\N	\N	3	06	549B2B	5.29	5.65	1.00	FC	Weeding man.	Ringan	6-Nov	4.00	22.60	18	2026-01-24 10:07:51	2026-01-28 13:37:48
155	19	534G1	\N	\N	3	05	534G1	5.93	5.93	5.60	FC	Weeding man.	Ringan	21-Jan	3.00	17.07	16	2026-01-24 10:08:36	2026-01-27 14:54:12
191	22	569A2	\N	\N	3	06	569A2	9.60	11.35	1.60	FC	Weeding man.	Ringan	21-Jan	3.00	34.05	16	2026-01-24 10:08:36	2026-01-27 14:54:12
82	21	554E2A	\N	\N	3	06	554E2A	4.42	4.42	8.20	FC	Weeding man.	Ringan	6-Nov	3.00	13.26	18	2026-01-24 10:07:51	2026-01-28 13:37:48
107	23	566B2	\N	\N	3	06	566B2	7.30	7.30	9.50	SC	Weeding man.	Ringan	7-Nov	3.00	21.90	18	2026-01-24 10:07:51	2026-01-28 13:37:48
\.


--
-- Data for Name: drones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.drones (id, judul, lokasi, tanggal_perencanaan, pdf_path, pdf_filename, user_id, created_at, updated_at, persen_gulma) FROM stdin;
1	Perencanaan Pengendalian Gulma Wilayah 16	502D1	2025-08-14	drones/1769228533_502D1.pdf	1769228533_502D1.pdf	\N	2026-01-24 11:22:14	2026-01-24 11:22:14	3.07
2	Perencanaan Gulma Wilayah 16	503C	2025-01-14	drones/1769391532_503C.pdf	1769391532_503C.pdf	\N	2026-01-26 08:38:53	2026-01-26 08:38:53	0.59
4	Perencanaan Gulma Wilayah 18	536A2	2025-02-11	drones/1769392034_536A2.pdf	1769392034_536A2.pdf	\N	2026-01-26 08:47:14	2026-01-26 08:47:14	3.75
5	Perencanaan Gulma Wilayah 17	520H	2025-03-08	drones/1769392238_520H.pdf	1769392238_520H.pdf	\N	2026-01-26 08:50:38	2026-01-26 08:50:38	0.07
6	Perencanaan Gulma Wilayah 19	542D2B	2025-04-17	drones/1769392554_542D2B.pdf	1769392554_542D2B.pdf	\N	2026-01-26 08:55:54	2026-01-26 08:55:54	6.24
7	Perencanaan Gulma Wilayah 19	533G1	2025-05-04	drones/1769392823_533G1_(1).pdf	1769392823_533G1_(1).pdf	\N	2026-01-26 09:00:23	2026-01-26 09:00:23	1.55
8	Perencanaan Gulma Wilayah 17	519e3a	2025-06-11	drones/1769393067_519E3A.pdf	1769393067_519E3A.pdf	\N	2026-01-26 09:04:27	2026-01-26 09:04:27	0.87
9	Perencanaan Gulma Wilayah 23	572B2	2025-07-18	drones/1769393309_572B2.pdf	1769393309_572B2.pdf	\N	2026-01-26 09:08:29	2026-01-26 09:08:29	1.18
10	Perencanaan Gulma Wilayah 21	550E1B	2025-08-13	drones/1769393538_550E1B.pdf	1769393538_550E1B.pdf	\N	2026-01-26 09:12:18	2026-01-26 09:12:18	2.00
11	Perencanaan Gulma Wilayah 20	584B1	2025-09-11	drones/1769393769_584B1.pdf	1769393769_584B1.pdf	\N	2026-01-26 09:16:09	2026-01-26 09:16:09	0.66
12	Perencanaan Gulma Wilayah 19	546F2	2025-10-08	drones/1769393954_546F2.pdf	1769393954_546F2.pdf	\N	2026-01-26 09:19:14	2026-01-26 09:19:14	2.31
13	Perencanaan Gulma Wilayah 21	550D2B	2025-11-03	drones/1769394074_550D2B.pdf	1769394074_550D2B.pdf	\N	2026-01-26 09:21:14	2026-01-26 09:21:14	1.11
14	Perencanaan Gulma Wilayah 19	546A2	2025-12-06	drones/1769394210_546A2.pdf	1769394210_546A2.pdf	\N	2026-01-26 09:23:30	2026-01-26 09:23:30	1.35
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: gulma_photos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.gulma_photos (id, kategori, foto_path, deskripsi, uploaded_by, file_size, mime_type, is_primary, created_at, updated_at, deleted_at) FROM stdin;
1	bersih	gulma_photos/gulma_bersih_1769485934_6978366ee1890.jpg	\N	1	133413	image/jpeg	t	2026-01-27 10:52:14	2026-01-27 10:52:14	\N
2	sedang	gulma_photos/gulma_sedang_1769485956_697836846a11f.jpg	\N	1	128654	image/jpeg	t	2026-01-27 10:52:36	2026-01-27 10:52:36	\N
3	ringan	gulma_photos/gulma_ringan_1769485975_69783697d89c1.jpg	\N	1	143212	image/jpeg	f	2026-01-27 10:52:55	2026-01-27 10:52:55	\N
4	berat	gulma_photos/gulma_berat_1769485990_697836a6123f2.jpg	\N	1	71290	image/jpeg	t	2026-01-27 10:53:10	2026-01-27 10:53:10	\N
5	sedang	gulma_photos/gulma_sedang_1769488332_69783fcc1fdce.jpg	\N	1	128654	image/jpeg	t	2026-01-27 11:32:12	2026-01-27 11:32:12	\N
\.


--
-- Data for Name: import_logs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.import_logs (id, nama_file, wilayah_id, tahun, bulan, minggu, jumlah_records, jumlah_berhasil, jumlah_gagal, status, error_log, user_id, created_at, updated_at) FROM stdin;
1	1 November.csv	16,17,18,19,20,21,22,23	2025	1	1	108	108	0	success	\N	1	2026-01-24 10:07:51	2026-01-24 10:07:51
2	Minggu3.csv	16,17,18,19,20,21,22,23	2026	2	3	140	140	0	success	\N	1	2026-01-24 10:08:36	2026-01-24 10:08:36
3	2 November.csv	16,17,18,19,20,21,22,23	2026	3	2	112	112	0	success	\N	1	2026-01-24 10:09:05	2026-01-24 10:09:05
4	Minggu3.csv	16,17,18,19,20,21,22,23	2027	3	3	140	140	0	success	\N	1	2026-01-25 22:16:18	2026-01-25 22:16:19
5	Minggu3.csv	16,17,18,19,20,21,22,23	2027	3	3	140	140	0	success	\N	1	2026-01-25 22:25:44	2026-01-25 22:25:45
6	Minggu3.csv	16,17,18,19,20,21,22,23	2027	3	3	140	140	0	success	\N	1	2026-01-25 22:27:53	2026-01-25 22:27:54
7	Minggu3.csv	16,17,18,19,20,21,22,23	2027	3	3	140	140	0	success	\N	1	2026-01-25 22:34:44	2026-01-25 22:34:45
8	Minggu3.csv	16,17,18,19,20,21,22,23	2027	3	3	140	140	0	success	\N	1	2026-01-25 22:36:01	2026-01-25 22:36:03
9	Minggu3.csv	16,17,18,19,20,21,22,23	2027	3	3	140	140	0	success	\N	1	2026-01-25 22:40:50	2026-01-25 22:40:51
10	Minggu3.csv	16,17,18,19,20,21,22,23	2027	3	3	140	140	0	success	\N	1	2026-01-25 22:41:27	2026-01-25 22:41:28
11	Minggu3.csv	16,17,18,19,20,21,22,23	2027	3	3	140	140	0	success	\N	1	2026-01-25 22:53:49	2026-01-25 22:53:50
12	4 Desember.csv	16,17,18,19,20,21,22,23	2026	1	3	134	134	0	success	\N	1	2026-01-26 08:10:28	2026-01-26 08:10:29
13	Minggu3 (2).csv	16,17,18,19,20,21,22,23	2026	8	3	140	140	0	success	\N	1	2026-01-26 08:12:18	2026-01-26 08:12:19
14	Minggu3.csv	16,17,18,19,20,21,22,23	2027	3	3	140	140	0	success	\N	1	2026-01-26 08:16:33	2026-01-26 08:16:34
15	Minggu3.csv	16,17,18,19,20,21,22,23	2031	3	3	140	140	0	success	\N	1	2026-01-27 11:30:33	2026-01-27 11:30:34
16	Minggu3.csv	16,17,18,19,20,21,22,23	2027	3	3	140	140	0	success	\N	1	2026-01-27 14:54:11	2026-01-27 14:54:12
17	1 November.csv	16,17,18,19,20,21,22,23	2027	1	1	108	108	0	success	\N	1	2026-01-27 14:55:13	2026-01-27 14:55:13
18	1 November.csv	16,17,18,19,20,21,22,23	2026	2	3	108	108	0	success	\N	1	2026-01-28 13:37:47	2026-01-28 13:37:48
\.


--
-- Data for Name: map_publications; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.map_publications (id, status, published_at, published_by, notes, created_at, updated_at, import_log_id, tahun, bulan, minggu) FROM stdin;
1	published	2026-01-24 10:08:02	1	\N	2026-01-24 10:07:51	2026-01-24 10:08:02	1	2025	1	1
3	published	2026-01-24 10:09:14	1	\N	2026-01-24 10:09:05	2026-01-24 10:09:14	3	2026	3	2
5	published	2026-01-26 08:10:41	1	\N	2026-01-26 08:10:29	2026-01-26 08:10:41	12	2026	1	3
6	published	2026-01-26 08:16:04	1	\N	2026-01-26 08:12:19	2026-01-26 08:16:04	13	2026	8	3
7	published	2026-01-27 11:30:34	1	\N	2026-01-27 11:30:34	2026-01-27 11:30:34	15	2031	3	3
4	published	2026-01-27 14:54:12	1	\N	2026-01-25 22:16:19	2026-01-27 14:54:12	16	2027	3	3
8	published	2026-01-27 15:06:54	1	\N	2026-01-27 14:55:13	2026-01-27 15:06:54	17	2027	1	1
2	published	2026-01-28 13:37:53	1	\N	2026-01-24 10:08:36	2026-01-28 13:37:53	18	2026	2	3
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_reset_tokens_table	1
3	2019_08_19_000000_create_failed_jobs_table	1
4	2019_12_14_000001_create_personal_access_tokens_table	1
5	2025_12_23_025339_create_import_logs_table	1
6	2025_12_23_025340_create_wilayah_table	1
7	2025_12_23_025341_create_data_gulma_table	1
8	2025_12_23_151816_remove_wilayah_foreign_key_from_data_gulma	1
9	2025_12_24_014652_add_csv_columns_to_data_gulma_table	1
10	2025_12_24_020717_ensure_import_log_id_exists_in_data_gulma	1
11	2025_12_24_020723_ensure_import_log_id_exists_in_data_gulma	1
12	2025_12_24_023543_change_wilayah_id_to_string_in_import_logs	1
13	2025_12_24_025417_create_map_publications_table	1
14	2025_12_27_063909_create_gulma_photos_table	1
15	2025_12_30_150000_add_import_log_id_to_map_publications	1
16	2026_01_02_000000_add_periode_to_map_publications	1
17	2026_01_12_112209_create_drones_table	1
18	2026_01_12_120000_add_persen_gulma_to_drones_table	1
19	2026_01_14_000001_drop_unique_constraint_data_gulma	1
20	2026_01_14_000001_restructure_data_gulma_table	1
21	2026_01_15_000001_fix_data_gulma_unique_constraint	1
22	2026_01_15_000002_fix_duplicate_data_gulma_records	1
23	2026_01_22_000001_change_tanggal_to_text_in_data_gulma	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, role, is_active, remember_token, created_at, updated_at) FROM stdin;
1	Administrator	admin@gmail.com	\N	$2y$12$U8pkCtinQe5XZWjDD/6FZuf7tOsAwxZ6SCEVWa6EkdMyDBrM7D4Ra	admin	t	\N	2026-01-24 10:06:50	2026-01-24 10:06:50
\.


--
-- Data for Name: wilayah; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.wilayah (id, wilayah_id, nama_wilayah, deskripsi, created_at, updated_at) FROM stdin;
1	16	Wilayah 16	Area produksi wilayah 16	2026-01-24 10:06:34	2026-01-24 10:06:34
2	17	Wilayah 17	Area produksi wilayah 17	2026-01-24 10:06:34	2026-01-24 10:06:34
3	18	Wilayah 18	Area produksi wilayah 18	2026-01-24 10:06:34	2026-01-24 10:06:34
4	19	Wilayah 19	Area produksi wilayah 19	2026-01-24 10:06:34	2026-01-24 10:06:34
5	20	Wilayah 20	Area produksi wilayah 20	2026-01-24 10:06:34	2026-01-24 10:06:34
6	21	Wilayah 21	Area produksi wilayah 21	2026-01-24 10:06:34	2026-01-24 10:06:34
7	22	Wilayah 22	Area produksi wilayah 22	2026-01-24 10:06:34	2026-01-24 10:06:34
8	23	Wilayah 23	Area produksi wilayah 23	2026-01-24 10:06:34	2026-01-24 10:06:34
\.


--
-- Name: data_gulma_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.data_gulma_id_seq', 351, true);


--
-- Name: drones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.drones_id_seq', 14, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: gulma_photos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.gulma_photos_id_seq', 5, true);


--
-- Name: import_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.import_logs_id_seq', 18, true);


--
-- Name: map_publications_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.map_publications_id_seq', 8, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 23, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: wilayah_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.wilayah_id_seq', 8, true);


--
-- Name: data_gulma data_gulma_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_gulma
    ADD CONSTRAINT data_gulma_pkey PRIMARY KEY (id);


--
-- Name: drones drones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drones
    ADD CONSTRAINT drones_pkey PRIMARY KEY (id);


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
-- Name: gulma_photos gulma_photos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gulma_photos
    ADD CONSTRAINT gulma_photos_pkey PRIMARY KEY (id);


--
-- Name: import_logs import_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.import_logs
    ADD CONSTRAINT import_logs_pkey PRIMARY KEY (id);


--
-- Name: map_publications map_publications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.map_publications
    ADD CONSTRAINT map_publications_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: map_publications unique_period_publication; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.map_publications
    ADD CONSTRAINT unique_period_publication UNIQUE (tahun, bulan, minggu);


--
-- Name: data_gulma unique_wil_feature_import; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_gulma
    ADD CONSTRAINT unique_wil_feature_import UNIQUE (wilayah_id, id_feature, import_log_id);


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
-- Name: wilayah wilayah_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wilayah
    ADD CONSTRAINT wilayah_pkey PRIMARY KEY (id);


--
-- Name: wilayah wilayah_wilayah_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wilayah
    ADD CONSTRAINT wilayah_wilayah_id_unique UNIQUE (wilayah_id);


--
-- Name: gulma_photos_kategori_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX gulma_photos_kategori_index ON public.gulma_photos USING btree (kategori);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: drones drones_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drones
    ADD CONSTRAINT drones_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: gulma_photos gulma_photos_uploaded_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gulma_photos
    ADD CONSTRAINT gulma_photos_uploaded_by_foreign FOREIGN KEY (uploaded_by) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: import_logs import_logs_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.import_logs
    ADD CONSTRAINT import_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: map_publications map_publications_import_log_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.map_publications
    ADD CONSTRAINT map_publications_import_log_id_foreign FOREIGN KEY (import_log_id) REFERENCES public.import_logs(id) ON DELETE CASCADE;


--
-- Name: map_publications map_publications_published_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.map_publications
    ADD CONSTRAINT map_publications_published_by_foreign FOREIGN KEY (published_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

