import { createRoot } from '@wordpress/element';
import { useTextField, AppForm } from '@arcwp/gateway-forms';
import { Grid, GridProvider } from '@arcwp/gateway-grids';

const TicketsForm = ({ record }) => {
  const { Input: TitleField } = useTextField({
    name: 'title',
    label: 'Title',
    required: true, 
  });

  return (
    <AppForm
      collectionKey="tickets"
      recordId={record?.id}
      apiAuth={{
        username: process.env.WP_GATEWAY_API_USERNAME,
        password: process.env.WP_GATEWAY_API_PASSWORD,
      }}
    >
      <TitleField />
    </AppForm>
  );
};

const App = () => {
  const auth = {
    username: process.env.WP_GATEWAY_API_USERNAME,
    password: process.env.WP_GATEWAY_API_PASSWORD,
  };
  return (
    <div className="resolve-tickets-app">
      <GridProvider value={{ auth }}>
        <Grid
          collectionKey="tickets"
          viewType="table"
          singleViewComponent={TicketsForm}
        />
      </GridProvider>
    </div>
  );
};

const container = document.getElementById('tickets-app');
const root = createRoot(container);
root.render(<App />);